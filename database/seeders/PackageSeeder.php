<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Package, Question};

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package = new Package();
        $package->name = "Personal Medical History";
        $package->type = "Laboratory";
        $package->company = null;
        $package->amount = 0;
        $package->save();

        $array = [
            "Personal Medical History" => [
                ["Have you been diagnosed with Diabetes?", "Dichotomous"],
                ["Have you been diagnosed with a Cardiovascular Disease?", "Dichotomous"],
                ["Have you been diagnosed with Hypertension?", "Dichotomous"],
                ["Do you have Asthma?", "Dichotomous"],
                ["Have you been diagnosed with Cancer?", "Dichotomous"],
                ["Do you have Tuberculosis?", "Dichotomous"],
                ["Do you have Goiter?", "Dichotomous"],
                ["Have you been hospitalized?", "Dichotomous"],
                ["Have you undergone surgery?", "Dichotomous"],
                ["Do you regularly consult a doctor?", "Dichotomous"],
                ["Are you currently taking any medication?", "Dichotomous"],
                ["Are you currently taking any supplements?", "Dichotomous"]
            ],
            "Childhood and Past Medical History" => [
                ["Chickenpox (Bulutong)", "Dichotomous"],
                ["Mumps (Beke)", "Dichotomous"],
                ["Measles (Tigdas)", "Dichotomous"],
                ["Asthma", "Dichotomous"],
                ["Primary Complex", "Dichotomous"],
                ["Hepatitis", "Dichotomous"],
                ["Tuberculosis", "Dichotomous"],
                ["Amoebiasis", "Dichotomous"],
                ["HIV", "Dichotomous"],
                ["Others", "Text"]
            ],
            "Family Medical History" => [
                ["Diabetes", "Dichotomous"],
                ["Heart Disease", "Dichotomous"],
                ["Hypertension", "Dichotomous"],
                ["Asthma", "Dichotomous"],
                ["Cancer", "Dichotomous"],
                ["Tuberculosis", "Dichotomous"],
                ["Goiter", "Dichotomous"],
                ["Psychiatric", "Dichotomous"]
            ],
            "Lifestyle" => [
                ["Do you Smoke? Please indicate no. of years and no. of sticks per day.", "Text"],
                ["Do you drink alcohol? Please indicate no. of years and frequency.", "Text"],
                ["Do you exercise regularly? Please indicate frequency.", "Text"],
                ["What are your physical activities?", "Text"]
            ],
            "OB-Gyne History" => [
                ["With child", "Dichotomous"],
                ["NSD", "Dichotomous"],
                ["CS", "Dichotomous"],
            ],
            "Psychological Test" => [
            ],
            "Gastrointestinal" => [
                ["Normal", "Dichotomous"],
                ["Vomiting", "Dichotomous"],
                ["Constipation", "Dichotomous"],
                ["Diarrhea", "Dichotomous"],
                ["Use of Laxatives", "Dichotomous"],
                ["Pain", "Dichotomous"],
                ["Hemorrhoids", "Dichotomous"]
            ],
            "Genitourinary" => [
                ["Normal", "Dichotomous"],
                ["Dysuria", "Dichotomous"],
                ["Discharge", "Dichotomous"],
                ["Hernia", "Dichotomous"],
                ["Flank Pain", "Dichotomous"],
                ["Polyuria", "Dichotomous"],
                ["Nocturia", "Dichotomous"]
            ],
            "Hematologic" => [
                ["Normal", "Dichotomous"],
                ["Anemia", "Dichotomous"],
                ["Bruised Easily", "Dichotomous"],
                ["Bleeding Gums", "Dichotomous"]
            ],
            "Cardiovascular" => [
                ["Normal", "Dichotomous"],
                ["Chest Pain", "Dichotomous"],
                ["Fatigue", "Dichotomous"],
                ["Irregular Heart Rate", "Dichotomous"],
                ["Palpitations", "Dichotomous"],
                ["Orthopnea", "Dichotomous"],
                ["Bradycardia", "Dichotomous"],
                ["Tachycardia", "Dichotomous"]
            ],
            "Pulmonary" => [
                ["Normal", "Dichotomous"],
                ["Dyspnea", "Dichotomous"],
                ["Cough", "Dichotomous"],
                ["Abnormal Findings in the Previous X-ray", "Dichotomous"],
                ["Wheezing", "Dichotomous"],
                ["Crackles", "Dichotomous"],
                ["Hemoptysis", "Dichotomous"]
            ],
            "Gynecologic/Obstetric" => [
                ["Normal", "Dichotomous"],
                ["Irregular Menstruation", "Dichotomous"],
                ["Menopause", "Dichotomous"],
                ["Dysmenorrhea", "Dichotomous"],
                ["Discharge", "Dichotomous"],
                ["Obstetric History", "Dichotomous"],
                ["Pregnant", "Dichotomous"]
            ],
            "Muscoskeletal" => [
                ["Normal", "Dichotomous"],
                ["Joint Pains", "Dichotomous"],
                ["Myalgia", "Dichotomous"],
                ["Fracture", "Dichotomous"],
            ],
            "Neurological" => [
                ["Normal", "Dichotomous"],
                ["Headache", "Dichotomous"],
                ["Dizziness", "Dichotomous"],
                ["Tremors", "Dichotomous"],
                ["Seizure", "Dichotomous"],
                ["Numbness", "Dichotomous"],
                ["Level of Consciousness", "Dichotomous"],
                ["Insomnia", "Dichotomous"]
            ],
            "Endocrine" => [
                ["Normal", "Dichotomous"],
                ["Polyuria", "Dichotomous"],
                ["Neck Mass", "Dichotomous"],
                ["Polyphagia", "Dichotomous"],
                ["Polydipsia", "Dichotomous"]
            ],
            "Allergies" => [
                ["Are you allergic to food? Specify", "Text"],
                ["Are you allergic to medicine? Specify", "Text"]
            ],
        ];

        $pid = $package->id;

        foreach($array as $key => $questions){
            $category = new Question();
            $category->package_id = $pid;
            $category->name = $key;
            $category->type = "Category";
            $category->save();

            foreach($questions as $question){
                $q = new Question();
                $q->package_id = $pid;
                $q->category_id = $category->id;
                $q->name = $question[0];
                $q->type = $question[1];
                $q->save();
            }
        }

        // $category = new Question();
        // $category->package_id = $package->id;
        // $category->category_id = null;
        // $category->name = "Personal Medical History";
        // $category->type = "Category";
        // $category->save();

        // $questions = new Question();
        // $questions->package_id = $package->id;
        // $questions->category_id = $category->id;
        // $questions->name = "Have you been diagnosed with Diabetes?";
        // $questions->type = "Dichotomous";
        // $questions->save();
    }
}
