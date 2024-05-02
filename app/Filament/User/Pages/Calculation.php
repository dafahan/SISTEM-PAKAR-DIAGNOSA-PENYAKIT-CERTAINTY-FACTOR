<?php

namespace App\Filament\User\Pages;

use App\Models\Calculation as ModelsCalculation;
use App\Models\Questionnaire;
use App\Models\Symptom;
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
                            '0.1' => '5',
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
            // Convert to associative array
            $questionaireValue = [];
            $data = $this->data;

            for ($i = 0; $i < count($data) / 2; $i++) {
                $answerKey = 'answer'.$i;
                array_push($questionaireValue, $data[$answerKey]);
            }

            $symptoms = Symptom::all();

            foreach ($symptoms as $key => $symptom) {
                $symptom->value = $questionaireValue[$key];
            }

            // Implement CF
            dd($symptoms);

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
