<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Package, Question};

class SubjectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package = new Package();
        $package->name = "Medical Examination Report";
        $package->type = "Subjective";
        $package->company = null;
        $package->amount = 0;
        $package->save();

        $array = [
            "Personal Medical History" => [
                ["ENT Disorder", "Dichotomous"],
                ["Eye Problem", "Dichotomous"],
                ["Asthma", "Dichotomous"],
                ["Lung Disease", "Dichotomous"],
                ["Hypertension", "Dichotomous"],
                ["Heart Disease", "Dichotomous"],
                ["Endoctrine Disorder (DM, Thyroid D/o)", "Dichotomous"],
                ["Cancer or Tumor", "Dichotomous"],
                ["Head or Neck Injury", "Dichotomous"],
                ["Kidney or GIT, GUT Disorder", "Dichotomous"],
                ["Sexually Transmitted Disease", "Dichotomous"],
                ["Neurologic Disorder(faintingspells, seizures, mental d/o)", "Dichotomous"],
                ["Viral Infections(Chicken Pox, Measles)", "Dichotomous"],
                ["Allergy", "Dichotomous"],
                ["Operations", "Dichotomous"],
                ["Current Medication", "Text"]
            ],
            "Medication History" => [
                ["Current Medication", "Text"],
                ["Dosage", "Text"],
                ["Frequency", "Text"],
            ],
            "Family History and Personal-Social History" => [
                ["Asthma", "Dichotomous"],
                ["Blood Dyscrasia", "Dichotomous"],
                ["Cancer", "Dichotomous"],
                ["Diabetes mellitus", "Dichotomous"],
                ["Heart Disease", "Dichotomous"],
                ["Hypertension", "Dichotomous"],
                ["Thyroid Disease", "Dichotomous"],
                ["Tuberculosis", "Dichotomous"],
                ["Others", "Dichotomous"],
            ],
            "Smoking History" => [
                ["Sticks per day", "Text"],
                ["For how many years", "Text"]
            ],
            "Drinking History" => [
                ["How many shots per day", "Text"],
                ["How many shots per week", "Text"],
                ["How many shots per month", "Text"],
                ["How many bottle per day", "Text"],
                ["How many bottle per week", "Text"],
                ["How many bottle per month", "Text"]
            ],
            "Menstrual History" => [
                ["LMP", "Text"],
                ["PMP", "Text"],
                ["Duration", "Text"],
                ["Interval", "Text"]
            ],
            "Obstetrical History" => [
                ["GP", "Text"],
                ["NSD", "Text"],
                ["CS 2", "Text"]
            ],
            "Vital Signs" => [
                ["1st BP", "Text"],
                ["2nd BP", "Text"],
                ["3rd BP", "Text"],
                ["Pulse Rate", "Text"],
                ["Respiratory Rate", "Text"],
                ["Temperature", "Text"]
            ],
            "Anthropometrics" => [
                ["Height in CM", "Text"],
                ["Weight in kg", "Text"],
                ["BMI", "Text"],
                ["IBW", "Text"]
            ],
            "Visual Acuity" => [
                ["Right", "Text"],
                ["Left", "Text"],
                ["Corrected", "Dichotomous"]
            ],
            "Systemic Examination" => [
                ["Skin", "Text"],
                ["Head, Neck, Scalp", "Text"],
                ["Eyes, external", "Text"],
                ["Pupils, Opthalmoscopic", "Text"],
                ["Ears", "Text"],
                ["Nose, Sinuses", "Text"],
                ["Mouth, Throat", "Text"],
                ["Neck, LN, Thyroid", "Text"],
                ["Chest, Breast, Axilia", "Text"],
                ["Lungs", "Text"],
                ["Heart", "Text"],
                ["Abdomen", "Text"],
                ["Back", "Text"],
                ["Anus-rectum", "Text"],
                ["G-U System", "Text"],
                ["Inguinals, genitals", "Text"],
                ["Reflexes", "Text"],
                ["Extremeties", "Text"]
            ],
            "Medical Evaluation" => [
                ["Diagnostic Examination", "Text"],
                ["Laboratory Findings", "Text"],
                ["Complete Blood Count", "Text"],
                ["Urinalysis", "Text"],
                ["Fecalysis", "Text"],
                ["Ancillary Procedures", "Text"],
                ["Chest X-RAY", "Text"],
                ["ECG", "Text"],
                ["Papsmear", "Text"],
                ["Blood Chemistry", "Text"],
                ["Others", "Text"]
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
    }
}
