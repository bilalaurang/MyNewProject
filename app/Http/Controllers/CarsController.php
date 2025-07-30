<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarsController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        Log::info('Index: Fetched cars', ['count' => $cars->count()]);
        return view('car-result', compact('cars'));
    }

    public function create()
    {
        Log::info('Create: Loading car form');
        return view('car-form');
    }

    public function store(Request $request)
    {
        Log::info('Store: Received request data', $request->all());

        try {
            $validated = $request->validate([
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'price' => 'required|numeric|min:0',
                'kilometers' => 'required|numeric|min:0',
                'color' => 'required|string|max:255',
                'body_condition' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'mechanical_condition' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'engine_size' => 'required|numeric|min:0',
                'horsepower' => 'required|integer|min:0',
                'top_speed' => 'required|integer|min:0',
                'steering_side' => 'required|in:left,right',
                'wheel_size' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|max:255',
                'interior_color' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'seats' => 'required|integer|min:1',
                'doors' => 'required|integer|min:1',
                'showroom_info' => 'nullable|string|max:1000',
                'showroom_office' => 'nullable|string|max:255',
                'showroom_sales' => 'nullable|string|max:255',
                'showroom_website' => 'nullable|url|max:255',
                'showroom_location' => 'nullable|url|max:255',
                'showroom_social_instagram' => 'nullable|url|max:255',
                'showroom_social_facebook' => 'nullable|url|max:255',
            ]);

            $description = $this->generateAIDescription($validated);
            Log::info('Store: Validated Data', $validated);
            Log::info('Store: Generated Description', ['description' => $description]);

            $car = Car::create(array_merge($validated, ['description' => $description]));
            Log::info('Store: Car saved successfully', ['car_id' => $car->getKey()]);

            return redirect()->route('cars.index')->with('success', 'Car added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Store: Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Store: Failed to save car', ['error' => $e->getMessage(), 'input' => $request->all()]);
            return redirect()->back()->with('error', 'Failed to add car. Check logs: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Car $car)
    {
        $car = Car::findOrFail($car->getKey());
        Log::info('Show: Car details (refetched)', $car->toArray());
        $additionalDetails = $this->generateAdditionalDetails($car->toArray());
        return view('car-detail', compact('car', 'additionalDetails'));
    }

    public function update(Request $request, Car $car)
    {
        Log::info('Update: Received request data', $request->all());

        $currentDescription = $car->description ?? '';
        $submittedDescription = $request->input('description');
        $isDescriptionModified = $request->input('description_modified', '0') === '1';

        try {
            $validatedData = $request->validate([
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'price' => 'required|numeric|min:0',
                'kilometers' => 'required|numeric|min:0',
                'color' => 'required|string|max:255',
                'body_condition' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'mechanical_condition' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'engine_size' => 'required|numeric|min:0',
                'horsepower' => 'required|integer|min:0',
                'top_speed' => 'required|integer|min:0',
                'steering_side' => 'required|in:left,right',
                'wheel_size' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|max:255',
                'interior_color' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'seats' => 'required|integer|min:1',
                'doors' => 'required|integer|min:1',
                'showroom_info' => 'nullable|string|max:1000',
                'showroom_office' => 'nullable|string|max:255',
                'showroom_sales' => 'nullable|string|max:255',
                'showroom_website' => 'nullable|url|max:255',
                'showroom_location' => 'nullable|url|max:255',
                'showroom_social_instagram' => 'nullable|url|max:255',
                'showroom_social_facebook' => 'nullable|url|max:255',
            ]);

            $description = $currentDescription;
            if ($isDescriptionModified && $submittedDescription !== null) {
                if (strlen($submittedDescription) > 1000) {
                    return redirect()->back()->withErrors(['description' => 'The description must not be greater than 1000 characters.'])->withInput();
                }
                $description = $submittedDescription;
            }
            Log::info('Update: Description handled', ['modified' => $isDescriptionModified, 'current' => $currentDescription, 'submitted' => $submittedDescription, 'final' => $description]);

            $dataToUpdate = array_merge($validatedData, ['description' => $description]);
            Log::info('Update: Data to save', $dataToUpdate);

            $car->fill($dataToUpdate);
            $car->save();
            $updatedCar = Car::findOrFail($car->getKey());
            Log::info('Update: Car updated successfully', ['car_id' => $car->getKey(), 'updated_data' => $updatedCar->toArray()]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update: Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Update: Failed to update car', ['error' => $e->getMessage(), 'input' => $request->all()]);
            return redirect()->back()->with('error', 'Failed to update car. Check logs: ' . $e->getMessage())->withInput();
        }

        $cacheBust = time();
        return redirect()->route('cars.show', [$car->getKey(), 'cache' => $cacheBust])->with('success', 'Car added successfully!');
    }

    private function generateAIDescription($data)
    {
        $systemPrompt = "You are an AI assistant tasked with generating car and showroom descriptions for a car dealership website. Follow these strict boundaries:
            - Use only simple, human-like language with a friendly tone.
            - Avoid dashes (-), bullet points, or any AI formatting.
            - Keep car descriptions between 50 and 100 words, and showroom descriptions between 50 and 70 words.
            - Include SEO-friendly terms like 'affordable used cars', 'reliable car dealership', or 'luxury car features'.
            - Automatically correct unrealistic inputs (e.g., high horsepower for a small engine) based on your knowledge of typical car specifications.
            - If you lack specific data about a feature, skip it silently without mention.
            - Do not include personal opinions, jokes, or unrelated content.
            - Focus solely on the provided data and generate professional, relevant content.";

        $showroomSystemPrompt = "You are an AI assistant generating a single showroom description for a car dealership website. Follow these strict boundaries:
            - Use only simple, human-like language with a friendly tone.
            - Avoid dashes (-), bullet points, or any AI formatting.
            - Limit to one paragraph of 50-70 words.
            - Include SEO-friendly terms like 'affordable used cars', 'reliable car dealership', or 'high-quality vehicles'.
            - Base the description on the provided showroom info, emphasizing location, quality, and an invitation to visit.
            - Do not include personal opinions, jokes, or unrelated content.
            - Ensure the description is realistic and professional.";

        $carPrompt = "$systemPrompt Create a description for a {$data['year']} {$data['make']} {$data['model']} with {$data['kilometers']} km, {$data['color']} color, {$data['body_condition']} body condition, {$data['mechanical_condition']} mechanical condition, {$data['engine_size']}L engine, {$data['horsepower']} hp, top speed of {$data['top_speed']} km/h, {$data['steering_side']} steering, {$data['wheel_size']} inch wheels, a {$data['interior_color']} interior, {$data['seats']} seats, and {$data['doors']} doors. Price: \${$data['price']}. Use the provided data and adjust any unrealistic specs based on typical car standards.";

        $maxRetries = 3; // Increased to 3 for better reliability
        $retryDelay = 2; // seconds
        $carDescription = $this->callDeepSeekAPIWithRetry($carPrompt, 'Car Description', $data, $maxRetries, $retryDelay);
        
        $showroomDescription = '';
        if (!empty($data['showroom_info'])) {
            $cleanedShowroomInfo = str_replace(["\r\n", "\r", "\n"], " ", $data['showroom_info']);
            $showroomPrompt = "$showroomSystemPrompt Generate a single paragraph based on this input: '$cleanedShowroomInfo'.";
            $generatedDescription = $this->callDeepSeekAPIWithRetry($showroomPrompt, 'Showroom Description', $data, $maxRetries, $retryDelay);
            // Validate relevance: check for dealership-related keywords and word count
            $relevantKeywords = ['dealership', 'showroom', 'cars', 'vehicles'];
            $isRelevant = trim($generatedDescription) && strpos($generatedDescription, 'API failed') === false;
            $wordCount = str_word_count($generatedDescription);
            $hasKeyword = false;
            foreach ($relevantKeywords as $keyword) {
                if (stripos($generatedDescription, $keyword) !== false) {
                    $hasKeyword = true;
                    break;
                }
            }
            if ($isRelevant && $hasKeyword && $wordCount >= 50 && $wordCount <= 70) {
                $showroomDescription = $generatedDescription;
            }
        }
        
        $showroomSection = '';
        if (!empty($showroomDescription) || (!empty($data['showroom_office']) || !empty($data['showroom_sales']) || !empty($data['showroom_website']) || !empty($data['showroom_location']) || !empty($data['showroom_social_instagram']) || !empty($data['showroom_social_facebook']))) {
            $showroomSection .= "\n\nAbout the Showroom\n" . $showroomDescription;
            $contactDetails = [];
            if (!empty($data['showroom_office'])) $contactDetails[] = "Office: {$data['showroom_office']}";
            if (!empty($data['showroom_sales'])) $contactDetails[] = "Sales: {$data['showroom_sales']}";
            if (!empty($data['showroom_website'])) $contactDetails[] = "Website: {$data['showroom_website']}";
            if (!empty($data['showroom_location'])) $contactDetails[] = "Location: {$data['showroom_location']}";
            if (!empty($data['showroom_social_instagram'])) $contactDetails[] = "Instagram: {$data['showroom_social_instagram']}";
            if (!empty($data['showroom_social_facebook'])) $contactDetails[] = "Facebook: {$data['showroom_social_facebook']}";
            if (!empty($contactDetails)) {
                $showroomSection .= "\n" . implode("\n", $contactDetails);
            }
            return $carDescription . $showroomSection;
        }
        
        return $carDescription;
    }

    private function generateAdditionalDetails($data)
    {
        $systemPrompt = "You are an AI assistant generating extra car features for a dealership website. Follow these strict boundaries:
            - Use simple, human-like language with a friendly tone.
            - Avoid dashes (-), bullet points in paragraphs, or AI formatting.
            - Present features as a list, one per line, with no numbering or special characters.
            - Include only realistic features based on the car's year and model.
            - Use SEO-friendly terms like 'luxury car features' or 'advanced car technology'.
            - Limit to features that enhance appeal (e.g., no basic requirements like brakes).
            - Do not include personal opinions or unrelated content.";

        $additionalPrompt = "$systemPrompt Generate as many extra features as possible for a {$data['year']} {$data['make']} {$data['model']} to enhance its appeal. Include options like heated seats, automatic seats, sunroof, navigation, backup camera, leather seats, keyless entry, climate control, touch screen display, parking sensors, adaptive cruise control, premium sound system, roof rails, alloy wheels, ambient lighting, wireless charging, rear entertainment, panoramic roof, blind spot monitoring, lane keep assist, adaptive headlights, power folding mirrors, rain sensing wipers, sport exhaust, sport chrono package.";

        $maxRetries = 3; // Increased to 3 for better reliability
        $retryDelay = 2; // seconds
        $features = $this->callDeepSeekAPIWithRetry($additionalPrompt, 'Additional Details', $data, $maxRetries, $retryDelay);
        return $features;
    }

    private function callDeepSeekAPIWithRetry($prompt, $logContext, $data, $maxRetries, $retryDelay)
    {
        $attempt = 0;
        while ($attempt <= $maxRetries) {
            try {
                Log::info("Attempting DeepSeek API call for $logContext (Attempt $attempt)", ['prompt' => $prompt]);
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                ])->timeout(30)
                  ->post(env('OPENROUTER_API_URL'), [
                      'model' => env('DEEPSEEK_MODEL', 'deepseek-chat:free'),
                      'messages' => [
                          ['role' => 'system', 'content' => 'You are an AI assistant.'],
                          ['role' => 'user', 'content' => $prompt],
                      ],
                      'max_tokens' => 150,
                      'temperature' => 0.9,
                  ]);
                $result = $response->json();
                Log::info("DeepSeek API Response for $logContext (Attempt $attempt)", ['response' => $result]);
                if (isset($result['choices'][0]['message']['content'])) {
                    return trim($result['choices'][0]['message']['content']);
                }
                throw new \Exception("No valid response from DeepSeek API for $logContext. Response: " . json_encode($result));
            } catch (\Exception $e) {
                Log::error("API Error for $logContext (Attempt $attempt): " . $e->getMessage(), ['data' => $data]);
                if ($attempt === $maxRetries) {
                    Log::critical("Max retries reached for $logContext, API failed", ['error' => $e->getMessage()]);
                    return "API failed after $maxRetries attempts. Check logs for details.";
                }
                sleep($retryDelay);
                $attempt++;
            }
        }
        return "API request failed unexpectedly.";
    }
}