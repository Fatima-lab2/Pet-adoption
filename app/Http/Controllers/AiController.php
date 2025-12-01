<?php

namespace App\Http\Controllers;
 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AiController extends Controller
{
    public function showConsultation($animal_id, Request $request)
    {
       $language = $request->input('language', 'en');
        $valid_languages = ['ar', 'en', 'fr'];
        if (!in_array($language, $valid_languages)) {
            $language = 'en';
        }

        // Get animal info with status validation
        $animal = DB::selectOne("
            SELECT * FROM animal 
            WHERE animal_id = ?
            AND status IN ('available', 'adopted', 'under_medical_care', 'foster')
        ", [$animal_id]);

        if (!$animal) {
            abort(404, $this->getNotFoundError($language));
        }

        // Calculate age
        $birth_date = new \DateTime($animal->birth_date);
        $today = new \DateTime();
        $age = $today->diff($birth_date)->y;

        // Translations
        $translations = [
            'ar' => [
                'title' => 'استشارة طبية لـ',
                'animal_info' => 'معلومات الحيوان',
                'type' => 'النوع',
                'breed' => 'السلالة',
                'age' => 'العمر',
                'years' => 'سنوات',
                'status' => 'الحالة',
                'weight' => 'الوزن',
                'kg' => 'كغ',
                'gender' => 'الجنس',
                'color' => 'اللون',
                'behavior' => 'السلوك',
                'question' => 'اطرح سؤالك الطبي',
                'placeholder' => 'اكتب سؤالك عن صحة الحيوان...',
                'food_question' => 'أسئلة عن الطعام',
                'medicine_question' => 'أسئلة عن الأدوية',
                'behavior_question' => 'أسئلة عن السلوك',
                'vaccine_question' => 'أسئلة عن التطعيمات',
                'health_question' => 'أسئلة عن الصحة العامة',
                'submit' => 'احصل على استشارة',
                'loading' => 'جاري تحضير الإجابة...',
                'back' => '← العودة إلى لوحة التحكم'
            ],
            'en' => [
                'title' => 'Medical Consultation for',
                'animal_info' => 'Animal Information',
                'type' => 'Type',
                'breed' => 'Breed',
                'age' => 'Age',
                'years' => 'years',
                'status' => 'Status',
                'weight' => 'Weight',
                'kg' => 'kg',
                'gender' => 'Gender',
                'color' => 'Color',
                'behavior' => 'Behavior',
                'question' => 'Ask your medical question',
                'placeholder' => 'Write your question about the animal\'s health...',
                'food_question' => 'Feeding questions',
                'medicine_question' => 'Medicine questions',
                'behavior_question' => 'Behavior questions',
                'vaccine_question' => 'Vaccination questions',
                'health_question' => 'General health questions',
                'submit' => 'Get Consultation',
                'loading' => 'Preparing the answer...',
                'back' => '← Back to Dashboard'
            ],
            'fr' => [
                'title' => 'Consultation vétérinaire pour',
                'animal_info' => 'Informations sur l\'animal',
                'type' => 'Type',
                'breed' => 'Race',
                'age' => 'Âge',
                'years' => 'ans',
                'status' => 'Statut',
                'weight' => 'Poids',
                'kg' => 'kg',
                'gender' => 'Genre',
                'color' => 'Couleur',
                'behavior' => 'Comportement',
                'question' => 'Posez votre question médicale',
                'placeholder' => 'Écrivez votre question sur la santé de l\'animal...',
                'food_question' => 'Questions sur la nourriture',
                'medicine_question' => 'Questions sur les médicaments',
                'behavior_question' => 'Questions sur le comportement',
                'vaccine_question' => 'Questions sur les vaccins',
                'health_question' => 'Questions sur la santé générale',
                'submit' => 'Obtenir une consultation',
                'loading' => 'Préparation de la réponse...',
                'back' => '← Retour au tableau de bord'
            ]
        ];

         return view('ai_consultation', [
            'animal' => $animal,
            'age' => $age,
            'language' => $language,
            'translations' => $translations[$language],
            'all_translations' => $translations
        ]);
    }

    public function handleConsultation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|integer|exists:animal,animal_id',
            'question' => 'required|string|max:500',
            'language' => 'required|in:ar,en,fr'
        ]);
 if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $this->getValidationError($validator->errors()->first(), $request->language)
            ]);
        }

        try {
            $animal_id = $request->animal_id;
            $question = trim($request->question);
            $language = $request->language;

            // Get animal with status validation
            $animal = DB::selectOne("
                SELECT * FROM animal 
                WHERE animal_id = ?
                AND status IN ('available', 'adopted', 'under_medical_care', 'foster')
            ", [$animal_id]);

            if (!$animal) {
                return response()->json([
                    'success' => false,
                    'error' => $this->getNotFoundError($language)
                ]);
            }
            // Calculate age from birth_date
            $birth_date = new \DateTime($animal->birth_date);
            $today = new \DateTime();
            $age = $birth_date->diff($today)->y;

            // Get health records if available
           // Get health records if available
$health_history = [];

if ($animal->health_record_id) {
    $health_history = DB::select("
        SELECT 
            c.checkup_id, 
            MAX(c.checkup_date) AS checkup_date, 
            MAX(c.details) AS details, 
            MAX(c.next_checkup) AS next_checkup,
            GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ') AS medicines,
            GROUP_CONCAT(DISTINCT v.name SEPARATOR ', ') AS vaccines
        FROM checkup c
        LEFT JOIN checkupmedicine cm ON c.checkup_id = cm.checkup_id
        LEFT JOIN medicine m ON cm.medicine_id = m.medicine_id
        LEFT JOIN checkupvaccination cv ON c.checkup_id = cv.checkup_id
        LEFT JOIN vaccination v ON cv.vaccination_id = v.vaccination_id
        WHERE c.health_record_id = ?
        GROUP BY c.checkup_id
        ORDER BY checkup_date DESC
        LIMIT 3
    ", [$animal->health_record_id]);
}

            // Get feeding information if available
            $feeding_info = DB::select("
                SELECT 
                    afs.method, 
                    afs.frequency,
                    f.name as food_name, 
                    f.type as food_type,
                    f.description as food_desc
                FROM animalfeedingschedule afs
                LEFT JOIN foodfeedingdetails ffd ON afs.feeding_schedule_id = ffd.feeding_schedule_id
                LEFT JOIN food f ON ffd.food_id = f.food_id
                WHERE afs.animal_id = ?
            ", [$animal_id]);

            // Get current medications
            $current_medications = [];
            if ($animal->health_record_id) {
                $current_medications = DB::select("
                    SELECT 
                        m.name,
                        cm.dosage,
                        cm.frequency,
                        cm.details,
                        m.description
                    FROM checkup c
                    JOIN checkupmedicine cm ON c.checkup_id = cm.checkup_id
                    JOIN medicine m ON cm.medicine_id = m.medicine_id
                    WHERE c.health_record_id = ?
                    ORDER BY c.checkup_date DESC
                    LIMIT 3
                ", [$animal->health_record_id]);
            }

            // Get current vaccinations
            $current_vaccinations = [];
            if ($animal->health_record_id) {
                $current_vaccinations = DB::select("
                    SELECT 
                        v.name,
                        cv.dosage,
                        cv.details,
                        v.description,
                        cv.allergy
                    FROM checkup c
                    JOIN checkupvaccination cv ON c.checkup_id = cv.checkup_id
                    JOIN vaccination v ON cv.vaccination_id = v.vaccination_id
                    WHERE c.health_record_id = ?
                    ORDER BY c.checkup_date DESC
                    LIMIT 3
                ", [$animal->health_record_id]);
            }

            // Prepare context with structured data
            $context = [
                'animal' => [
                    'name' => $animal->animal_name,
                    'type' => $animal->type,
                    'breed' => $animal->breed,
                    'age' => $age,
                    'weight' => $animal->weight,
                    'status' => $animal->status,
                    'gender' => $animal->gender,
                    'color' => $animal->color,
                    'arrival_date' => $animal->arrival_date,
                    'room' => $animal->room_id ? 'Room '.$animal->room_id : 'Not assigned'
                ],
                'current_medications' => $current_medications,
                'current_vaccinations' => $current_vaccinations,
                'feeding_info' => $feeding_info,
                'health_history' => $health_history
            ];

            // Generate response based on context
            $response = $this->generateResponse($animal, $question, $context, $language);

            return response()->json([
                'success' => true,
                'response' => nl2br(htmlspecialchars($response))
            ]);

        } catch (\Exception $e) {
           
            return response()->json([
                'success' => false,
                'error' => $this->getDatabaseError($language) . ': ' . $e->getMessage()
            ]);
        }
    }
    

    private function generateResponse($animal, $question, $context, $language)
    {
        $question_lower = mb_strtolower($question, 'UTF-8');
        
        // French responses
        if ($language == 'fr') {
            // Health status
            if (strpos($question_lower, 'santé') !== false || strpos($question_lower, 'état') !== false) {
                $response = "État de santé de {$context['animal']['name']} ({$context['animal']['type']} - {$context['animal']['breed']}):\n";
                $response .= "• Âge: {$context['animal']['age']} ans\n";
                $response .= "• Poids: {$context['animal']['weight']} kg\n";
                $response .= "• Statut: {$context['animal']['status']}\n";
                $response .= "• Genre: {$context['animal']['gender']}\n";
                $response .= "• Couleur: {$context['animal']['color']}\n";
                $response .= "• Chambre: {$context['animal']['room']}\n";
                $response .= "• Date d'arrivée: " . date('d/m/Y', strtotime($context['animal']['arrival_date'])) . "\n\n";
                
                if (!empty($context['health_history'])) {
                    $response .= "Derniers examens:\n";
                    foreach ($context['health_history'] as $checkup) {
                        $date = date('d/m/Y', strtotime($checkup->checkup_date));
                        $response .= "• $date: {$checkup->details}\n";
                        if ($checkup->next_checkup) {
                            $next_date = date('d/m/Y', strtotime($checkup->next_checkup));
                            $response .= "  Prochain contrôle: $next_date\n";
                        }
                        if (!empty($checkup->medicines)) {
                            $response .= "  Médicaments: {$checkup->medicines}\n";
                        }
                        if (!empty($checkup->vaccines)) {
                            $response .= "  Vaccins: {$checkup->vaccines}\n";
                        }
                    }
                }
                
                return $response;
            }
            
            // Food questions
            if (strpos($question_lower, 'nourriture') !== false || strpos($question_lower, 'manger') !== false) {
                if (!empty($context['feeding_info'])) {
                    $response = "Programme alimentaire pour {$context['animal']['name']}:\n\n";
                    foreach ($context['feeding_info'] as $feeding) {
                        $response .= "• {$feeding->food_name} ({$feeding->food_type}): {$feeding->method} {$feeding->frequency}\n";
                        if (!empty($feeding->food_desc)) {
                            $response .= "  Description: {$feeding->food_desc}\n";
                        }
                    }
                    $response .= "\nConseils:\n- Eau fraîche toujours disponible\n- Pas de changement brusque de régime\n- Pesez l'animal régulièrement";
                } else {
                    $response = "Aucun programme alimentaire spécifique enregistré pour {$context['animal']['name']}. Consultez un vétérinaire pour des recommandations.";
                }
                return $response;
            }
            
            // Medicine questions
            if (strpos($question_lower, 'médicament') !== false || strpos($question_lower, 'traitement') !== false) {
                if (!empty($context['current_medications'])) {
                    $response = "Traitement en cours pour {$context['animal']['name']}:\n\n";
                    foreach ($context['current_medications'] as $med) {
                        $response .= "• {$med->name}: {$med->dosage} {$med->frequency}\n";
                        if (!empty($med->details)) {
                            $response .= "  Détails: {$med->details}\n";
                        }
                        if (!empty($med->description)) {
                            $response .= "  Description: {$med->description}\n";
                        }
                    }
                    $response .= "\nConseils d'administration:\n- Respectez les horaires\n- Suivez les doses prescrites\n- Observez les effets secondaires";
                } else {
                    $response = "{$context['animal']['name']} ne suit actuellement aucun traitement médicamenteux.";
                }
                return $response;
            }
            
            // Vaccination questions
            if (strpos($question_lower, 'vaccin') !== false) {
                if (!empty($context['current_vaccinations'])) {
                    $response = "Vaccins pour {$context['animal']['name']}:\n\n";
                    foreach ($context['current_vaccinations'] as $vax) {
                        $response .= "• {$vax->name}: {$vax->dosage} {$vax->details}\n";
                        if (!empty($vax->description)) {
                            $response .= "  Description: {$vax->description}\n";
                        }
                        if (!empty($vax->allergy)) {
                            $response .= "  Allergies: {$vax->allergy}\n";
                        }
                    }
                } else {
                    $response = "Aucun vaccin enregistré pour {$context['animal']['name']}.";
                }
                return $response;
            }
            
            // Behavior questions
            if (strpos($question_lower, 'comportement') !== false) {
                $response = "Comportement de {$context['animal']['name']}:\n\n";
                $response .= "• Évaluation actuelle: {$context['animal']['status']}\n";
                $response .= "• Chambre: {$context['animal']['room']}\n\n";
                
                $response .= "Conseils:\n";
                switch ($context['animal']['status']) {
                    case 'available':
                        $response .= "- Cet animal est sociable et prêt pour l'adoption\n";
                        break;
                    case 'under_medical_care':
                        $response .= "- Cet animal nécessite des soins médicaux spéciaux\n";
                        break;
                    case 'adopted':
                        $response .= "- Cet animal a déjà trouvé une famille\n";
                        break;
                    case 'foster':
                        $response .= "- Cet animal est en famille d'accueil\n";
                        break;
                    default:
                        $response .= "- Observez les changements de comportement\n- Consultez un vétérinaire si le comportement change soudainement";
                }
                
                return $response;
            }
            
            // Default response
            $response = "Informations sur {$context['animal']['name']} ({$context['animal']['type']} - {$context['animal']['breed']}):\n\n";
            $response .= "• Âge: {$context['animal']['age']} ans\n";
            $response .= "• Poids: {$context['animal']['weight']} kg\n";
            $response .= "• Statut: {$context['animal']['status']}\n";
            $response .= "• Genre: {$context['animal']['gender']}\n";
            $response .= "• Couleur: {$context['animal']['color']}\n";
            $response .= "• Chambre: {$context['animal']['room']}\n";
            $response .= "• Date d'arrivée: " . date('d/m/Y', strtotime($context['animal']['arrival_date'])) . "\n\n";
            
            if (!empty($context['current_medications'])) {
                $response .= "Médicaments actuels:\n";
                foreach ($context['current_medications'] as $med) {
                    $response .= "• {$med->name} ({$med->dosage} {$med->frequency})\n";
                }
                $response .= "\n";
            }
            
            if (!empty($context['current_vaccinations'])) {
                $response .= "Derniers vaccins:\n";
                foreach ($context['current_vaccinations'] as $vax) {
                    $response .= "• {$vax->name}\n";
                }
                $response .= "\n";
            }
            
            $response .= "Pour une réponse plus précise, posez une question spécifique sur:\n- La santé\n- La nourriture\n- Les médicaments\n- Les vaccins\n- Le comportement";
            
            return $response;
        }
        // Arabic responses
        elseif ($language == 'ar') {
            // Health status
            if (strpos($question_lower, 'صحة') !== false || strpos($question_lower, 'حالة') !== false) {
                $response = "حالة {$context['animal']['name']} الصحية ({$context['animal']['type']} - {$context['animal']['breed']}):\n";
                $response .= "• العمر: {$context['animal']['age']} سنة\n";
                $response .= "• الوزن: {$context['animal']['weight']} كغ\n";
                $response .= "• الحالة: {$context['animal']['status']}\n";
                $response .= "• الجنس: {$context['animal']['gender']}\n";
                $response .= "• اللون: {$context['animal']['color']}\n";
                $response .= "• الغرفة: {$context['animal']['room']}\n";
                $response .= "• تاريخ الوصول: " . date('d/m/Y', strtotime($context['animal']['arrival_date'])) . "\n\n";
                
                if (!empty($context['health_history'])) {
                    $response .= "آخر الفحوصات:\n";
                    foreach ($context['health_history'] as $checkup) {
                        $date = date('d/m/Y', strtotime($checkup->checkup_date));
                        $response .= "• $date: {$checkup->details}\n";
                        if ($checkup->next_checkup) {
                            $next_date = date('d/m/Y', strtotime($checkup->next_checkup));
                            $response .= "  الفحص القادم: $next_date\n";
                        }
                        if (!empty($checkup->medicines)) {
                            $response .= "  الأدوية: {$checkup->medicines}\n";
                        }
                        if (!empty($checkup->vaccines)) {
                            $response .= "  التطعيمات: {$checkup->vaccines}\n";
                        }
                    }
                }
                
                return $response;
            }
            
            // Food questions
            if (strpos($question_lower, 'طعام') !== false || strpos($question_lower, 'أكل') !== false) {
                if (!empty($context['feeding_info'])) {
                    $response = "برنامج التغذية لـ {$context['animal']['name']}:\n\n";
                    foreach ($context['feeding_info'] as $feeding) {
                        $response .= "• {$feeding->food_name} ({$feeding->food_type}): {$feeding->method} {$feeding->frequency}\n";
                        if (!empty($feeding->food_desc)) {
                            $response .= "  وصف: {$feeding->food_desc}\n";
                        }
                    }
                    $response .= "\nنصائح:\n- تأمين ماء نظيف دائماً\n- تجنب تغيير الطعام فجأة\n- مراقبة الوزن بانتظام";
                } else {
                    $response = "لا يوجد برنامج تغذية مسجل لـ {$context['animal']['name']}. استشر طبيباً بيطرياً للحصول على توصيات.";
                }
                return $response;
            }
            
            // Medicine questions
            if (strpos($question_lower, 'دواء') !== false || strpos($question_lower, 'علاج') !== false) {
                if (!empty($context['current_medications'])) {
                    $response = "العلاجات الحالية لـ {$context['animal']['name']}:\n\n";
                    foreach ($context['current_medications'] as $med) {
                        $response .= "• {$med->name}: {$med->dosage} {$med->frequency}\n";
                        if (!empty($med->details)) {
                            $response .= "  تفاصيل: {$med->details}\n";
                        }
                        if (!empty($med->description)) {
                            $response .= "  وصف: {$med->description}\n";
                        }
                    }
                    $response .= "\nنصائح للإعطاء:\n- احترم المواعيد\n- التزم بالجرعات المحددة\n- راقب الآثار الجانبية";
                } else {
                    $response = "{$context['animal']['name']} لا يتناول أي أدوية حالياً.";
                }
                return $response;
            }
            
            // Vaccination questions
            if (strpos($question_lower, 'تطعيم') !== false || strpos($question_lower, 'لقاح') !== false) {
                if (!empty($context['current_vaccinations'])) {
                    $response = "التطعيمات لـ {$context['animal']['name']}:\n\n";
                    foreach ($context['current_vaccinations'] as $vax) {
                        $response .= "• {$vax->name}: {$vax->dosage} {$vax->details}\n";
                        if (!empty($vax->description)) {
                            $response .= "  وصف: {$vax->description}\n";
                        }
                        if (!empty($vax->allergy)) {
                            $response .= "  حساسية: {$vax->allergy}\n";
                        }
                    }
                } else {
                    $response = "لا توجد تطعيمات مسجلة لـ {$context['animal']['name']}.";
                }
                return $response;
            }
            
            // Behavior questions
            if (strpos($question_lower, 'سلوك') !== false) {
                $response = "سلوك {$context['animal']['name']}:\n\n";
                $response .= "• التقييم الحالي: {$context['animal']['status']}\n";
                $response .= "• الغرفة: {$context['animal']['room']}\n\n";
                
                $response .= "نصائح:\n";
                switch ($context['animal']['status']) {
                    case 'available':
                        $response .= "- هذا الحيوان اجتماعي وجاهز للتبني\n";
                        break;
                    case 'under_medical_care':
                        $response .= "- هذا الحيوان يحتاج إلى رعاية طبية خاصة\n";
                        break;
                    case 'adopted':
                        $response .= "- هذا الحيوان تم تبنيه بالفعل\n";
                        break;
                    case 'foster':
                        $response .= "- هذا الحيوان في عائلة حاضنة\n";
                        break;
                    default:
                        $response .= "- راقب تغيرات السلوك\n- استشر طبيباً بيطرياً إذا تغير السلوك فجأة";
                }
                
                return $response;
            }
            
            // Default response
            $response = "معلومات عن {$context['animal']['name']} ({$context['animal']['type']} - {$context['animal']['breed']}):\n\n";
            $response .= "• العمر: {$context['animal']['age']} سنة\n";
            $response .= "• الوزن: {$context['animal']['weight']} كغ\n";
            $response .= "• الحالة: {$context['animal']['status']}\n";
            $response .= "• الجنس: {$context['animal']['gender']}\n";
            $response .= "• اللون: {$context['animal']['color']}\n";
            $response .= "• الغرفة: {$context['animal']['room']}\n";
            $response .= "• تاريخ الوصول: " . date('d/m/Y', strtotime($context['animal']['arrival_date'])) . "\n\n";
            
            if (!empty($context['current_medications'])) {
                $response .= "الأدوية الحالية:\n";
                foreach ($context['current_medications'] as $med) {
                    $response .= "• {$med->name} ({$med->dosage} {$med->frequency})\n";
                }
                $response .= "\n";
            }
            
            if (!empty($context['current_vaccinations'])) {
                $response .= "آخر التطعيمات:\n";
                foreach ($context['current_vaccinations'] as $vax) {
                    $response .= "• {$vax->name}\n";
                }
                $response .= "\n";
            }
            
            $response .= "للحصول على إجابة أكثر دقة، اطرح سؤالاً محدداً عن:\n- الصحة\n- الطعام\n- الأدوية\n- التطعيمات\n- السلوك";
            
            return $response;
        }
        // English responses (default)
        else {
            // Health status
            if (strpos($question_lower, 'health') !== false || strpos($question_lower, 'status') !== false) {
                $response = "Health status of {$context['animal']['name']} ({$context['animal']['type']} - {$context['animal']['breed']}):\n";
                $response .= "• Age: {$context['animal']['age']} years\n";
                $response .= "• Weight: {$context['animal']['weight']} kg\n";
                $response .= "• Status: {$context['animal']['status']}\n";
                $response .= "• Gender: {$context['animal']['gender']}\n";
                $response .= "• Color: {$context['animal']['color']}\n";
                $response .= "• Room: {$context['animal']['room']}\n";
                $response .= "• Arrival date: " . date('m/d/Y', strtotime($context['animal']['arrival_date'])) . "\n\n";
                
                if (!empty($context['health_history'])) {
                    $response .= "Recent checkups:\n";
                    foreach ($context['health_history'] as $checkup) {
                        $date = date('m/d/Y', strtotime($checkup->checkup_date));
                        $response .= "• $date: {$checkup->details}\n";
                        if ($checkup->next_checkup) {
                            $next_date = date('m/d/Y', strtotime($checkup->next_checkup));
                            $response .= "  Next checkup: $next_date\n";
                        }
                        if (!empty($checkup->medicines)) {
                            $response .= "  Medicines: {$checkup->medicines}\n";
                        }
                        if (!empty($checkup->vaccines)) {
                            $response .= "  Vaccines: {$checkup->vaccines}\n";
                        }
                    }
                }
                
                return $response;
            }
            
            // Food questions
            if (strpos($question_lower, 'food') !== false || strpos($question_lower, 'eat') !== false) {
                if (!empty($context['feeding_info'])) {
                    $response = "Feeding schedule for {$context['animal']['name']}:\n\n";
                    foreach ($context['feeding_info'] as $feeding) {
                        $response .= "• {$feeding->food_name} ({$feeding->food_type}): {$feeding->method} {$feeding->frequency}\n";
                        if (!empty($feeding->food_desc)) {
                            $response .= "  Description: {$feeding->food_desc}\n";
                        }
                    }
                    $response .= "\nTips:\n- Always provide fresh water\n- Avoid sudden diet changes\n- Monitor weight regularly";
                } else {
                    $response = "No specific feeding schedule recorded for {$context['animal']['name']}. Consult a vet for recommendations.";
                }
                return $response;
            }
            
            // Medicine questions
            if (strpos($question_lower, 'medicine') !== false || strpos($question_lower, 'treatment') !== false) {
                if (!empty($context['current_medications'])) {
                    $response = "Current treatment for {$context['animal']['name']}:\n\n";
                    foreach ($context['current_medications'] as $med) {
                        $response .= "• {$med->name}: {$med->dosage} {$med->frequency}\n";
                        if (!empty($med->details)) {
                            $response .= "  Details: {$med->details}\n";
                        }
                        if (!empty($med->description)) {
                            $response .= "  Description: {$med->description}\n";
                        }
                    }
                    $response .= "\nAdministration tips:\n- Stick to schedule\n- Follow prescribed doses\n- Watch for side effects";
                } else {
                    $response = "{$context['animal']['name']} is not currently on any medication.";
                }
                return $response;
            }
            
            // Vaccination questions
            if (strpos($question_lower, 'vaccin') !== false) {
                if (!empty($context['current_vaccinations'])) {
                    $response = "Vaccinations for {$context['animal']['name']}:\n\n";
                    foreach ($context['current_vaccinations'] as $vax) {
                        $response .= "• {$vax->name}: {$vax->dosage} {$vax->details}\n";
                        if (!empty($vax->description)) {
                            $response .= "  Description: {$vax->description}\n";
                        }
                        if (!empty($vax->allergy)) {
                            $response .= "  Allergies: {$vax->allergy}\n";
                        }
                    }
                } else {
                    $response = "No vaccinations recorded for {$context['animal']['name']}.";
                }
                return $response;
            }
            
            // Behavior questions
            if (strpos($question_lower, 'behavior') !== false || strpos($question_lower, 'behaviour') !== false) {
                $response = "Status of {$context['animal']['name']}:\n\n";
                $response .= "• Current status: {$context['animal']['status']}\n";
                $response .= "• Room: {$context['animal']['room']}\n\n";
                
                $response .= "Information:\n";
                switch ($context['animal']['status']) {
                    case 'available':
                        $response .= "- This animal is sociable and ready for adoption\n";
                        break;
                    case 'under_medical_care':
                        $response .= "- This animal requires special medical care\n";
                        break;
                    case 'adopted':
                        $response .= "- This animal has already found a family\n";
                        break;
                    case 'foster':
                        $response .= "- This animal is in foster care\n";
                        break;
                    default:
                        $response .= "- Observe behavior changes\n- Consult a vet if behavior changes suddenly";
                }
                
                return $response;
            }
            
            // Default response
            $response = "Information about {$context['animal']['name']} ({$context['animal']['type']} - {$context['animal']['breed']}):\n\n";
            $response .= "• Age: {$context['animal']['age']} years\n";
            $response .= "• Weight: {$context['animal']['weight']} kg\n";
            $response .= "• Status: {$context['animal']['status']}\n";
            $response .= "• Gender: {$context['animal']['gender']}\n";
            $response .= "• Color: {$context['animal']['color']}\n";
            $response .= "• Room: {$context['animal']['room']}\n";
            $response .= "• Arrival date: " . date('m/d/Y', strtotime($context['animal']['arrival_date'])) . "\n\n";
            
            if (!empty($context['current_medications'])) {
                $response .= "Current medications:\n";
                foreach ($context['current_medications'] as $med) {
                    $response .= "• {$med->name} ({$med->dosage} {$med->frequency})\n";
                }
                $response .= "\n";
            }
            
            if (!empty($context['current_vaccinations'])) {
                $response .= "Recent vaccinations:\n";
                foreach ($context['current_vaccinations'] as $vax) {
                    $response .= "• {$vax->name}\n";
                }
                $response .= "\n";
            }
            
            $response .= "For a more precise answer, ask a specific question about:\n- Health\n- Food\n- Medication\n- Vaccinations\n- Behavior";
            
            return $response;
        }
    }

    private function getValidationError($message, $language)
    {
        if ($language == 'fr') {
            return "Erreur de validation: $message";
        } elseif ($language == 'ar') {
            return "خطأ في التحقق: $message";
        }
        return "Validation error: $message";
    }

    private function getNotFoundError($language)
    {
        if ($language == 'fr') {
            return "Aucun animal trouvé avec cet ID";
        } elseif ($language == 'ar') {
            return "ﻻ يوجد حيوان مسجل بهذا الرقم";
        }
        return "No animal found with this ID";
    }

    private function getDatabaseError($language)
    {
        if ($language == 'fr') {
            return "Erreur de base de données";
        } elseif ($language == 'ar') {
            return "خطأ في قاعدة البيانات";
        }
        return "Database error";
    }
}