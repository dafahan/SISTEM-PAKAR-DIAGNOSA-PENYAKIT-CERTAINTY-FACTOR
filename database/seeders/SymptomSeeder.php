<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Disease::insert([
            [
                'name' => 'Anorexia Nervosa',
            ],
            [
                'name' => 'Bulimia Nervosa',
            ],
            [
                'name' => 'Binge Eating Disorder',
            ],
            [
                'name' => 'ARFID',
            ],
        ]);

        Symptom::insert([
            [
                'name' => 'Extreme weight loss that is not in line with weight standards',
            ],
            [
                'name' => 'Frequently skipping meals and making excuses not to eat',
            ],
            [
                'name' => 'Enjoys cooking food for others but doesn\'t eat it herself',
            ],
            [
                'name' => 'Reluctant to eat in public',
            ],
            [
                'name' => 'Only eat certain foods',
            ],
            [
                'name' => 'Having excessive fear of weight gain',
            ],
            [
                'name' => 'Have a habit of weighing yourself repeatedly',
            ],
            [
                'name' => 'Often look in the mirror to find your own shortcomings',
            ],
            [
                'name' => 'Often complains of being overweight where others think it is not the case',
            ],
            [
                'name' => 'Tends to lie about how much food has been consumed',
            ],
            [
                'name' => 'Very concerned with weight and body shape',
            ],
            [
                'name' => 'Always think negatively of their own body shape',
            ],
            [
                'name' => 'Fear of being fat or feeling overweight',
            ],
            [
                'name' => 'Often get out of control when eating, such as continuing to eat until your stomach hurts or eating excessive portions',
            ],
            [
                'name' => 'Frequent rush to the bathroom after meals',
            ],
            [
                'name' => 'Forcing oneself to vomit, especially by inserting a finger into the esophagus',
            ],
            [
                'name' => 'Exercising excessively',
            ],
            [
                'name' => 'Using laxatives, diuretics, or enemas after meals',
            ],
            [
                'name' => 'Using supplements or herbal products for weight loss',
            ],
            [
                'name' => 'Have cuts, scars or calluses on knuckles or hands',
            ],
            [
                'name' => 'Eating large meals over a period of time such as a 2-hour period',
            ],
            [
                'name' => 'Chews much faster than normal people',
            ],
            [
                'name' => 'Eating to the point of feeling too full and making your stomach growl',
            ],
            [
                'name' => 'Can eat large portions even when not hungry',
            ],
            [
                'name' => 'Eating secretly because she was embarrassed by the amount of food.',
            ],
            [
                'name' => 'Feeling disgusted, depressed, ashamed, upset or guilty about yourself after eating',
            ],
            [
                'name' => 'Frequent dieters but find it difficult to stick to a diet and lose weight',
            ],
            [
                'name' => 'Hoarding food',
            ],
            [
                'name' => 'Feeling sensitive, upset or angry when talking about food or hearing about body shaming',
            ],
            [
                'name' => 'Having feelings of anxiety, hopelessness, and low self-confidence',
            ],
            [
                'name' => 'Sudden or severe restriction of the type or amount of food eaten',
            ],
            [
                'name' => 'Will only eat foods with a certain texture',
            ],
            [
                'name' => 'Vomiting while eating, or fear of choking',
            ],
            [
                'name' => 'Decrease in appetite or interest in food',
            ],
            [
                'name' => 'A small number of preferred foods that decrease over time (i.e. pick and choose foods that get worse)',
            ],
            [
                'name' => 'No body image anxiety or fear of weight gain',
            ],
        ]);
    }
}
