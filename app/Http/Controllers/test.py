from transformers import pipeline

# Initialize the text generation pipeline with explicit settings
generator = pipeline(
    'text-generation',
    model='distilgpt2',
    truncation=True,
    max_length=100,  # Use max_length instead of max_new_tokens
    pad_token_id=50256,  # Match eos_token_id
    no_repeat_ngram_size=3  # Prevent repetition of 3-word sequences
)

# Generate a sample car description with a clear stop condition
prompt = "Generate a concise description (50-100 words) for a car: Make: Toyota, Model: Supra, Year: 2003, Price: $8000, Kilometers: 20000, Color: black, Body Condition: good, Mechanical Condition: best, Engine Size: 3.0L, Horsepower: 500. END DESCRIPTION"
result = generator(
    prompt,
    max_length=100,
    num_return_sequences=1,
    truncation=True,
    stop=["END DESCRIPTION", "."],  # Stop at end marker or sentence end
    early_stopping=True  # Force stop at max_length
)

# Print the generated text
print(result[0]['generated_text'])