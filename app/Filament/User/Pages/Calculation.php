<?php

namespace App\Filament\User\Pages;

use App\Models\Symptom;
use App\Models\Rule;
use App\Models\Disease;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;


class Calculation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.calculation';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $symptomps = Symptom::all()->toArray();

        $chunks = array_chunk($symptomps, 7);

        $steps = [];
        $inputs = [];
        $counter = 0;

        // Associate each input section
        foreach ($chunks as $key => $chunk) {
            $tempInputs = [];
            foreach ($chunk as $symptom) {
                $input = [
                    TextInput::make('symptom'.$counter)
                        ->default($symptom['id'])
                        ->hidden(),
                    Radio::make('answer'.$counter)
                        ->label($symptom['name'])
                        ->inline()
                        ->required()
                        ->options([
                            '0.2' => '1',
                            '0.4' => '2',
                            '0.6' => '3',
                            '0.8' => '4',
                            '1.0' => '5',
                        ]),
                ];

                $counter++;
                array_push($tempInputs, $input);
            }

            array_push($inputs, $tempInputs);
            $tempInputs = [];
        }

        // Associate the steps with the input
        foreach ($chunks as $key => $chunk) {
            array_push($steps, Step::make($key)
                ->label(null)
                ->schema(array_merge(...$inputs[$key])));
        }

        return $form
            ->schema([
                Wizard::make(
                    $steps
                )
                    ->submitAction(
                        Action::make('submit')
                            ->label('Submit')
                            ->action('submit')
                            ->button()
                    ),
            ])
            ->statePath('data');
    }


    public function submit()
    {
        try {
            $data = $this->data;
    
            // Process questionnaire data
            $questionnaireValues = [];
            for ($i = 0; $i < count($data) / 2; $i++) {
                $answerKey = 'answer' . $i;
                $questionnaireValues[] = doubleval($data[$answerKey]);
            }
    
            // Retrieve all symptoms
            $symptoms = Symptom::all();
    
            // Prepare items
            $items = [];
            foreach ($symptoms as $key => $symptom) {
                $items[$symptom->id] = [
                    'name' => $symptom->name,
                    'value' => doubleval($questionnaireValues[$key]) * $symptom->belief,
                ];
            }
    
            // Retrieve diseases and rules
            $diseases = Disease::pluck('name', 'id')->map(function ($name) {
                return [$name, 0];
            })->toArray();
    
            $combine = array_fill_keys(array_keys($diseases), []);
            $result = [];
    
            // Apply rules to calculate disease probabilities
            foreach (Rule::all() as $rule) {
                $diseaseId = $rule->disease_id;
                $symptomId = $rule->symptom_id;
    
                if (!isset($items[$symptomId])) {
                    continue;
                }
    
                $value = $items[$symptomId]['value'];
                if (empty($combine[$diseaseId])) {
                    $combine[$diseaseId][] = $value;
                } else {
                    $prevValue = end($combine[$diseaseId]);
                    $combine[$diseaseId][] = $prevValue + $value * (1 - $prevValue);
                }
            }
    
            // Calculate final scores
            foreach ($diseases as $diseaseId => $diseaseInfo) {
                $scores = $combine[$diseaseId];
                $score = end($scores) * 100;
    
                $result[$diseaseId] = [
                    'name' => $diseaseInfo[0],
                    'score' => $score,
                ];
            }
    
            // Determine the disease with the highest score
            $maxScore = -1;
            $maxScoreDisease = null;
            foreach ($result as $diseaseId => $diseaseInfo) {
                if ($diseaseInfo['score'] > $maxScore) {
                    $maxScore = $diseaseInfo['score'];
                    $maxScoreDisease = $diseaseInfo;
                }
            }
    
            // Default disease if no match is found
            $diagnosedDisease = $maxScoreDisease ? $maxScoreDisease['name'] : 'Unknown';
    
            // Save the calculation history using raw query
            $calculationId = DB::table('calculations')->insertGetId([
                'user_id' => auth()->id(),
                'disease_id' => $maxScoreDisease ? $diseaseId : null,
                'value' => $maxScore,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Save detailed analysis
            $details = [];
            foreach ($result as $id => $diseaseInfo) {
                $details[] = [
                    'calculation_id' => $calculationId,
                    'disease_id' => $id,
                    'value' => $diseaseInfo['score'],
                ];
            }
            DB::table('calculation_details')->insert($details);
    
            // Save questionnaire answers
            $answers = [];
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'answer')) {
                    $index = intval(substr($key, 6));
                    $answers[] = [
                        'calculation_id' => $calculationId,
                        'symptom_id' => $data['symptom' . $index],
                        'answer' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
    
            // Redirect to history page with success notification
            Notification::make()
                ->title('Calculation saved successfully!')
                ->success()
                ->send();
    
            return redirect()->route('filament.resources.history.index');
        } catch (\Throwable $th) {
            // Handle errors
            Notification::make()
                ->title('An error occurred while saving the calculation.')
                ->danger()
                ->send();
            throw $th;
        }
    }
    
    
}
