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
        // AI-driven validation using xAI automotive knowledge
        $year = (int)$data['year'];
        $make = strtolower($data['make']);
        $model = strtolower($data['model']);
        $engineSize = (float)$data['engine_size'];
        $horsepower = (int)$data['horsepower'];
        $topSpeed = (int)$data['top_speed'];
        $kilometers = (int)$data['kilometers'];
        $seats = (int)$data['seats'];
        $doors = (int)$data['doors'];

        // Known makes based on xAI knowledge
        $knownMakes = ['toyota', 'honda', 'ford', 'bmw', 'audi', 'mercedes', 'volkswagen', 'tesla', 'porsche', 'ferrari'];
        $isValidInput = true;
        $invalidInputDetail = '';

        // Check for invalid inputs
        if (empty($make) || empty($model)) {
            $isValidInput = false;
            $invalidInputDetail = empty($make) ? 'make' : 'model';
        } elseif (!in_array($make, $knownMakes)) {
            $isValidInput = false;
            $invalidInputDetail = "make '$make'";
        } else {
            // Model-specific validation
            if ($make === 'toyota' && $model === 'civic') {
                if ($year < 1972 || $year > 2026) {
                    $isValidInput = false;
                    $invalidInputDetail = "year $year for $make $model";
                } elseif ($year <= 2000 && ($engineSize > 2.0 || $horsepower > 200)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                } elseif ($year > 2000 && ($engineSize > 2.4 || $horsepower > 300)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                }
            } elseif ($make === 'honda' && $model === 'civic') {
                if ($year < 1972 || $year > 2026) {
                    $isValidInput = false;
                    $invalidInputDetail = "year $year for $make $model";
                } elseif ($year <= 2000 && ($engineSize > 2.0 || $horsepower > 200)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                } elseif ($year > 2000 && ($engineSize > 2.4 || $horsepower > 300)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                }
            } elseif ($make === 'toyota' && $model === 'corolla') {
                if ($year < 1966 || $year > 2026) {
                    $isValidInput = false;
                    $invalidInputDetail = "year $year for $make $model";
                } elseif ($year <= 2000 && ($engineSize > 2.0 || $horsepower > 150)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                } elseif ($year > 2000 && ($engineSize > 2.4 || $horsepower > 200)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                }
            } elseif ($make === 'ford' && $model === 'focus') {
                if ($year < 1998 || $year > 2026) {
                    $isValidInput = false;
                    $invalidInputDetail = "year $year for $make $model";
                } elseif ($year <= 2000 && ($engineSize > 2.0 || $horsepower > 130)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                } elseif ($year > 2000 && ($engineSize > 2.5 || $horsepower > 350)) {
                    $isValidInput = false;
                    $invalidInputDetail = "engine size $engineSize or horsepower $horsepower for $year $make $model";
                }
            } elseif ($year < 1886 || $year > date('Y') + 1) {
                $isValidInput = false;
                $invalidInputDetail = "year $year";
            } elseif ($engineSize <= 0 || $engineSize > 8 || $horsepower <= 0 || $horsepower > 1000 || $topSpeed <= 0 || $topSpeed > 500 || $kilometers < 0 || $kilometers > 1000000 || $seats < 1 || $seats > 9 || $doors < 1 || $doors > 6) {
                $isValidInput = false;
                $invalidInputDetail = "specs (engine $engineSize, hp $horsepower, top speed $topSpeed, km $kilometers, seats $seats, doors $doors)";
            }
        }

        if (!$isValidInput) {
            Log::warning('generateAIDescription: Skipping description generation due to invalid input', $data);
            return [
                'description' => "This input $invalidInputDetail does not exist. Please check your input.",
                'showroom' => '',
                'mixed_overview' => "This input $invalidInputDetail does not exist. Please check your input.",
                'main_features' => '',
            ];
        }

        $maxRetries = 3;
        $retryDelay = 2;

        // 1. Description (Generated by DeepSeek) - Updated Prompt with <br> and Short Content
        $descriptionSystemPrompt = "You are an AI assistant generating a car description for a dealership website. Follow these strict boundaries:\n- Use simple, warm language.\n- Structure with a bolded header (e.g., **{$data['year']} {$data['make']} {$data['model']}, {$data['engine_size']}L engine, {$data['horsepower']} hp, {$data['steering_side']} steering – {$data['price']} PKR**) on the first line, a two-line intro (each with <br>), bolded 'Key Features:' with bullets (each with <br>), and 1 closing line with <br>.\n- Keep under 60 words.\n- Include 'affordable used car'.\n- Base on {$data['year']} {$data['make']} {$data['model']}, {$data['kilometers']} km, {$data['color']} color, {$data['body_condition']} body, {$data['mechanical_condition']} mechanical, {$data['wheel_size']} wheels, {$data['seats']} seats, {$data['doors']} doors, price {$data['price']} PKR.\n- 'Key Features:' includes mileage, condition, wheels, exterior, interior, seats/doors.\n- Use <br> for breaks.";
        $descriptionPrompt = "$descriptionSystemPrompt Generate a short description.";
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
            - Focus only on main features: year, make, model, body condition, mechanical condition, engine size, horsepower, seats, and doors.
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