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
            $questionaireValue = [];
            $data = $this->data;

            for ($i = 0; $i < count($data) / 2; $i++) {
                $answerKey = 'answer'.$i;
                array_push($questionaireValue, $data[$answerKey]);
            }

            $symptoms = Symptom::all();
            $items = [];
            foreach ($symptoms as $key => $symptom) {
                // Create a new Symptom array with the desired attributes
                $newSymptom = [
                    'name' => $symptom->name,
                    'value' => doubleval($questionaireValue[$key])*$symptom->belief,
                ];

                // Add the new Symptom array to the $items dictionary with the symptom ID as key
                $items[$symptom->id] = $newSymptom;
            }
            
            $diseases = Disease::pluck('name', 'id')->map(function ($name) {
                return [$name, 0];
            })->toArray();
            
            // Get the keys of the diseases array
            $diseaseIds = array_keys($diseases);
            $combine = [];
                foreach ($diseaseIds as $diseaseId) {
                    $combine[$diseaseId] = [];
             } 
            // Create $isFirstCombine array with keys same as $diseases and values set to 0
            $isFirstCombine = array_combine($diseaseIds, array_fill(1, count($diseaseIds), 1));
            $rules = Rule::all()->toArray();
            $firstIndex = 0;
           
            foreach($rules as $index => $rule){
                $disease_id = $rule['disease_id'];
                if($isFirstCombine[$disease_id]==2){
                    $combine[$disease_id][] = $items[$firstIndex]['value'] + $items[$rule['symptom_id']]['value'] * (1-$items[$firstIndex]['value']);
                }else if($isFirstCombine[$disease_id]==1){
                    $firstIndex = $rule['symptom_id'];
                    $isFirstCombine[$disease_id] = 2 ;
                }else{
                    $tmp = end($combine[$disease_id]);
                    $combine[$disease_id][] = $tmp + $items[$rule['symptom_id']]['value'] * (1-$tmp);
                }

            }
            // Initialize the $result array
            $result = [];

            // Iterate over each disease
            foreach ($diseases as $diseaseId => $diseaseInfo) {
                // Get the disease name and initial score from $diseaseInfo
                $diseaseName = $diseaseInfo[0];
                $initialScore = $diseaseInfo[1];

                // Get the score from $combine using the disease ID
                $scores = $combine[$diseaseId];
                $score = end($scores) * 100; // Get the last score and multiply by 100

                // Add the disease information to the $result array
                $result[$diseaseId] = [
                    'name' => $diseaseName,
                    'score' => $score,
                ];
            }

            // Now $result contains the desired structure with disease names and scores
            //dd($result);
            $maxScore = -1; // Initial value to ensure any positive score will be considered
            $maxScoreDisease = null;

            // Iterate over each disease in the $result array
            foreach ($result as $diseaseId => $diseaseInfo) {
                // Get the score of the current disease
                $score = $diseaseInfo['score'];
                
                // Check if the current score is greater than the max score encountered so far
                if ($score > $maxScore) {
                    // If yes, update the max score and corresponding disease name
                    $maxScore = $score;
                    $maxScoreDisease = $diseaseInfo['name'];
                }
            }

            // Now $maxScoreDisease contains the name of the disease with the highest score
            // and $maxScore contains the corresponding score
            $disease = $maxScoreDisease;
            $score = $maxScore;
            // Output the result
            //dd($disease, $score);
            
            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();

            $this->redirect(route('filament.user.pages.calculation'));
        } catch (\Throwable $th) {
            dd($th);
            Notification::make()
                ->title('An error occured while calculating the data')
                ->danger()
                ->send();
        }
    }
}
