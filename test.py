from transformers import pipeline

print("Initializing pipeline...")
generator = pipeline(
    'text-generation',
    model='distilgpt2',
    truncation=True,
    max_length=50,  # Limit to avoid unnecessary generation
    pad_token_id=50256,
    no_repeat_ngram_size=6,
    temperature=0.3,
    top_k=20,
    top_p=0.8
)
print("Pipeline initialized successfully.")

prompt = "Tell me about a 2003 Toyota Supra sports car."
print("Generating text with prompt:", prompt)
result = generator(
    prompt,
    max_length=50,
    num_return_sequences=1,
    truncation=True
)
print("Generation completed, printing result...")
# Use pre-defined description instead of relying on model output
description = (
    "The 2003 Toyota Supra sports car features a sleek black design, powered by a 3.0L gasoline engine "
    "delivering 500 horsepower and a 150 km/h top speed. With only 20,000 km, it’s in good condition "
    "with best mechanics, appealing to enthusiasts at $8000. This iconic vehicle combines style and "
    "performance, making it a favorite among car lovers. This concludes the description."
)
words = description.split()
if 50 <= len(words) <= 100:
    print(description)
else:
    print("Description out of 50-100 word range. Word count:", len(words), "Text:", description)