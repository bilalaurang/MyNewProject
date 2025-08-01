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
                'description' => 'nullable|string|max:1000', // Optional user input
                'showroom_info' => 'nullable|string|max:1000', // Optional user input
                'showroom_office' => 'nullable|string|max:255',
                'showroom_sales' => 'nullable|string|max:255',
                'showroom_website' => 'nullable|url|max:255',
                'showroom_location' => 'nullable|url|max:255',
                'showroom_social_instagram' => 'nullable|url|max:255',
                'showroom_social_facebook' => 'nullable|url|max:255',
            ]);

            $generatedContent = $this->generateAIDescription($validated);
            $car = Car::create(array_merge($validated, $generatedContent));
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
        $descriptions = $this->generateAIDescription($car->toArray());
        return view('car-detail', compact('car', 'additionalDetails', 'descriptions'));
    }

    public function update(Request $request, Car $car)
    {
        Log::info('Update: Received request data', $request->all());

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
                'description' => 'nullable|string|max:1000', // Optional user input
                'showroom_info' => 'nullable|string|max:1000', // Optional user input
                'showroom_office' => 'nullable|string|max:255',
                'showroom_sales' => 'nullable|string|max:255',
                'showroom_website' => 'nullable|url|max:255',
                'showroom_location' => 'nullable|url|max:255',
                'showroom_social_instagram' => 'nullable|url|max:255',
                'showroom_social_facebook' => 'nullable|url|max:255',
            ]);

            $dataToUpdate = $validatedData;
            $generatedContent = $this->generateAIDescription($validatedData);
            $car->fill(array_merge($dataToUpdate, $generatedContent));
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
        return redirect()->route('cars.show', [$car->getKey(), 'cache' => $cacheBust])->with('success', 'Car updated successfully!');
    }

    private function generateAIDescription($data)
    {
        $maxRetries = 3;
        $retryDelay = 2;

        // 1. Description (Generated by DeepSeek)
        $descriptionSystemPrompt = "You are an AI assistant generating a car description for a dealership website based on car specifications. Follow these strict boundaries:
            - Use simple, human-like language with a friendly tone.
            - Avoid dashes (-), bullet points, or any AI formatting.
            - Keep the description between 50 and 70 words.
            - Include SEO-friendly terms like 'high-performance car' or 'luxury vehicle'.
            - Base the description on the provided data: a {$data['year']} {$data['make']} {$data['model']} with {$data['kilometers']} km, {$data['color']} color, {$data['body_condition']} body condition, {$data['mechanical_condition']} mechanical condition, {$data['engine_size']}L engine, {$data['horsepower']} hp, {$data['top_speed']} km/h top speed, {$data['steering_side']} steering, {$data['wheel_size']} inch wheels, {$data['interior_color']} interior, {$data['seats']} seats, and {$data['doors']} doors. Price: {$data['price']} PKR.
            - Do not include showroom details or personal opinions.";
        $descriptionPrompt = "$descriptionSystemPrompt Generate a detailed car description.";
        $description = $this->callDeepSeekAPIWithRetry($descriptionPrompt, 'Description', $data, $maxRetries, $retryDelay);
        Log::info('Description (Generated)', ['prompt' => $descriptionPrompt, 'content' => $description]);

        // 2. Showroom Details (Generated by DeepSeek, optional)
        $showroomInfo = $data['showroom_info'] ?? '';
        $isValidShowroomInfo = $showroomInfo && preg_match('/\b(dealership|showroom|car|service|experience|location|inventory|sales)\b/i', $showroomInfo);
        $showroomDescription = '';
        if ($isValidShowroomInfo) {
            $contactDetails = [];
            if (!empty($data['showroom_office'])) $contactDetails[] = "office: {$data['showroom_office']}";
            if (!empty($data['showroom_sales'])) $contactDetails[] = "sales: {$data['showroom_sales']}";
            if (!empty($data['showroom_website'])) $contactDetails[] = "website: {$data['showroom_website']}";
            if (!empty($data['showroom_location'])) $contactDetails[] = "location: {$data['showroom_location']}";
            if (!empty($data['showroom_social_instagram'])) $contactDetails[] = "Instagram: {$data['showroom_social_instagram']}";
            if (!empty($data['showroom_social_facebook'])) $contactDetails[] = "Facebook: {$data['showroom_social_facebook']}";
            $contactClause = !empty($contactDetails) ? ' Contact us at ' . implode(', ', $contactDetails) . '.' : '';

            $showroomSystemPrompt = "You are an AI assistant generating showroom details for a dealership website. Follow these strict boundaries:
                - Use simple, human-like language with a friendly tone.
                - Avoid dashes (-), bullet points, or any AI formatting.
                - Keep the description between 50 and 70 words.
                - Include SEO-friendly terms like 'reliable car dealership' or 'affordable used cars'.
                - Enhance the user-provided showroom info ('$showroomInfo') with details like experience, services, and location.
                - Include contact details only if provided: $contactClause
                - Do not include personal opinions or unrelated content.";
            $showroomPrompt = "$showroomSystemPrompt Generate a professional showroom description.";
            $showroomDescription = $this->callDeepSeekAPIWithRetry($showroomPrompt, 'Showroom Details', $data, $maxRetries, $retryDelay);
            Log::info('Showroom Details (Generated)', ['prompt' => $showroomPrompt, 'content' => $showroomDescription]);
        }

        // 4. Mixed Overview (Generated by DeepSeek)
        $mixedOverviewSystemPrompt = "You are an AI assistant generating a mixed description blending car details and showroom information for a dealership website. Follow these strict boundaries:
            - Use simple, human-like language with a friendly tone.
            - Avoid dashes (-), bullet points, or any AI formatting.
            - Keep the description between 50 and 100 words.
            - Include SEO-friendly terms like 'reliable car dealership', 'top-quality vehicles', or 'luxury car features'.
            - Alternate between highlighting car features (based on a {$data['year']} {$data['make']} {$data['model']} with {$data['kilometers']} km, {$data['color']} color, etc.) and showroom benefits (based on '$showroomInfo' if valid, otherwise skip showroom details).
            - Emphasize the showroom’s role in providing top-quality cars only if showroom info is valid.
            - Do not include personal opinions.";
        $mixedOverviewPrompt = "$mixedOverviewSystemPrompt Generate a mixed overview combining car and showroom details.";
        $mixedOverviewDescription = $this->callDeepSeekAPIWithRetry($mixedOverviewPrompt, 'Mixed Overview', $data, $maxRetries, $retryDelay);
        Log::info('Mixed Overview (Generated)', ['prompt' => $mixedOverviewPrompt, 'content' => $mixedOverviewDescription]);

        // 5. Main Features (Generated by DeepSeek)
        $mainFeaturesSystemPrompt = "You are an AI assistant generating a concise description of main car features for a dealership website. Follow these strict boundaries:
            - Use simple, human-like language with a friendly tone.
            - Avoid dashes (-) or any AI formatting.
            - Present features as a list, one per line, prefixed with a bullet (•) for display as bullets.
            - Include SEO-friendly terms like 'luxury car features' or 'high-performance vehicle'.
            - Focus only on main features: year, make, model, kilometers, color, body condition, mechanical condition, engine size, horsepower, top speed, steering side, wheel size, interior color, seats, and doors.
            - Do not include showroom details or personal opinions.";
        $mainFeaturesPrompt = "$mainFeaturesSystemPrompt Generate main features for a {$data['year']} {$data['make']} {$data['model']} with {$data['kilometers']} km, {$data['color']} color, {$data['body_condition']} body condition, {$data['mechanical_condition']} mechanical condition, {$data['engine_size']}L engine, {$data['horsepower']} hp, {$data['top_speed']} km/h top speed, {$data['steering_side']} steering, {$data['wheel_size']} inch wheels, {$data['interior_color']} interior, {$data['seats']} seats, and {$data['doors']} doors.";
        $mainFeaturesDescription = $this->callDeepSeekAPIWithRetry($mainFeaturesPrompt, 'Main Features', $data, $maxRetries, $retryDelay);
        Log::info('Main Features (Generated)', ['prompt' => $mainFeaturesPrompt, 'content' => $mainFeaturesDescription]);

        return [
            'description' => $description,
            'showroom' => $showroomDescription,
            'mixed_overview' => $mixedOverviewDescription,
            'main_features' => $mainFeaturesDescription,
        ];
    }

    private function generateAdditionalDetails($data)
    {
        $additionalFeaturesSystemPrompt = "You are an AI assistant generating extra car features for a dealership website. Follow these strict boundaries:
            - Use simple, human-like language with a friendly tone.
            - Avoid dashes (-), bullet points in paragraphs, or AI formatting.
            - Present features as a list, one per line, with no numbering or special characters.
            - Include only realistic features based on the car's year and model.
            - Use SEO-friendly terms like 'luxury car features' or 'advanced car technology'.
            - Limit to features that enhance appeal (e.g., no basic requirements like brakes).
            - Do not include personal opinions or unrelated content.";
        $additionalFeaturesPrompt = "$additionalFeaturesSystemPrompt Generate as many extra features as possible for a {$data['year']} {$data['make']} {$data['model']} to enhance its appeal. Include options like heated seats, automatic seats, sunroof, navigation, backup camera, leather seats, keyless entry, climate control, touch screen display, parking sensors, adaptive cruise control, premium sound system, roof rails, alloy wheels, ambient lighting, wireless charging, rear entertainment, panoramic roof, blind spot monitoring, lane keep assist, adaptive headlights, power folding mirrors, rain sensing wipers, sport exhaust, sport chrono package.";
        $features = $this->callDeepSeekAPIWithRetry($additionalFeaturesPrompt, 'Additional Details', $data, 3, 2);
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