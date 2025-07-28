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
                'make' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'model' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'price' => 'required|numeric|min:0',
                'kilometers' => 'required|numeric|min:0',
                'color' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
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
        // Fetch the latest data from the database
        $car = Car::findOrFail($car->id);
        Log::info('Show: Car details (refetched)', $car->toArray());
        return view('car-detail', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        Log::info('Update: Received request data', $request->all());

        // Get current and submitted description
        $currentDescription = $car->description ?? '';
        $submittedDescription = $request->input('description');
        $isDescriptionModified = $request->input('description_modified', '0') === '1';

        // Validate all fields except description
        try {
            $validatedData = $request->validate([
                'make' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'model' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'price' => 'required|numeric|min:0',
                'kilometers' => 'required|numeric|min:0',
                'color' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
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
            ]);

            // Handle description only if modified
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
            $updatedCar = Car::findOrFail($car->id);
            Log::info('Update: Car updated successfully', ['car_id' => $car->id, 'updated_data' => $updatedCar->toArray()]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update: Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Update: Failed to update car', ['error' => $e->getMessage(), 'input' => $request->all()]);
            return redirect()->back()->with('error', 'Failed to update car. Check logs: ' . $e->getMessage())->withInput();
        }

        // Force refresh with cache-busting parameter
        $cacheBust = time();
        return redirect()->route('cars.show', [$car->id, 'cache' => $cacheBust])->with('success', 'Car updated successfully!');
    }

    private function generateAIDescription($data)
    {
        $carPrompt = "Generate a 50-100 word description for a {$data['year']} {$data['make']} {$data['model']} with {$data['kilometers']} km, {$data['color']} color, {$data['body_condition']} body condition, {$data['mechanical_condition']} mechanical condition, {$data['engine_size']}L engine, {$data['horsepower']} hp, top speed of {$data['top_speed']} km/h, {$data['steering_side']} steering, {$data['wheel_size']} inch wheels, {$data['interior_color']} interior, {$data['seats']} seats, and {$data['doors']} doors. Price: \${$data['price']}. Create a unique, engaging description with varied tone, style, and additional details (e.g., driving experience, design highlights) to differentiate it from other car descriptions.";
        
        $carDescription = $this->callGeminiAPI($carPrompt, 'Car Description', $data);
        
        if (!empty($data['showroom_info'])) {
            $showroomPrompt = "Generate a 50-70 word professional description for a car showroom or company based on the following raw input: '{$data['showroom_info']}'. Include a professional tone, highlight expertise, quality, or unique aspects, and end with an invitation to visit or explore their collection.";
            $showroomDescription = $this->callGeminiAPI($showroomPrompt, 'Showroom Description', $data);
            $showroomSection = "\n\nAbout the Showroom\n$showroomDescription\n\nContact Us\nOffice: Not provided\nSales: Not provided\nWebsite: www.example.com\nLocation: Not provided\nOpen: 7 days a week, 10 AM to 9 PM (Sunday 10 AM to 7 PM)\n\nStay Connected\nInstagram: instagram.com/example\nFacebook: facebook.com/example";
            return $carDescription . $showroomSection;
        }
        
        return $carDescription;
    }

    private function callGeminiAPI($prompt, $logContext, $data)
    {
        Log::info("Attempting Gemini API call for $logContext", ['prompt' => $prompt]);
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent', [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 200,
                    'temperature' => 0.9,
                ],
            ]);
            $result = $response->json();
            Log::info("Gemini API Response for $logContext", ['response' => $result]);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return trim($result['candidates'][0]['content']['parts'][0]['text']);
            }
            throw new \Exception("No description returned from Gemini API for $logContext");
        } catch (\Exception $e) {
            Log::error("AI Description Error for $logContext: " . $e->getMessage());
            if ($logContext === 'Car Description') {
                $introPhrases = [
                    'Step into the driver’s seat of this',
                    'Unleash the potential of this',
                    'Experience the allure of this',
                    'Command the road with this',
                    'Discover the charm of this',
                    'Feel the pulse of this',
                ];
                $stylePhrases = [
                    'boasting sleek lines and bold styling',
                    'with a timeless design that turns heads',
                    'featuring aggressive contours and sporty flair',
                    'crafted with elegance and sophistication',
                    'showcasing a rugged yet refined look',
                ];
                $drivePhrases = [
                    'perfect for thrilling weekend drives',
                    'ideal for smooth city commutes',
                    'built for high-performance adventures',
                    'designed for comfort on long journeys',
                    'tailored for dynamic handling and fun',
                ];
                $closingPhrases = [
                    'Grab this automotive masterpiece today!',
                    'Your dream ride awaits—don’t miss out!',
                    'A rare find for enthusiasts and collectors!',
                    'Ready to redefine your driving experience!',
                    'Seize this chance to own a legend!',
                ];
                $intro = $introPhrases[array_rand($introPhrases)];
                $style = $stylePhrases[array_rand($stylePhrases)];
                $drive = $drivePhrases[array_rand($drivePhrases)];
                $closing = $closingPhrases[array_rand($closingPhrases)];
                $conditionAdjective = strtolower($data['body_condition']) === 'best' ? 'pristine' : 'well-maintained';
                $powerAdjective = $data['horsepower'] > 500 ? 'exhilarating' : 'reliable';
                $priceAdjective = $data['price'] < 5000 ? 'budget-friendly' : 'premium';
                return "$intro $conditionAdjective {$data['year']} {$data['make']} {$data['model']} with only {$data['kilometers']} km. Priced at \${$data['price']}, this $priceAdjective ride offers a $powerAdjective {$data['engine_size']}L engine with {$data['horsepower']} hp and a top speed of {$data['top_speed']} km/h. It features {$data['steering_side']} steering, {$data['wheel_size']} inch wheels, a {$data['interior_color']} interior, {$data['seats']} seats, and {$data['doors']} doors, $style. $drive. $closing";
            }
            return "Showroom information unavailable.";
        }
    }
}