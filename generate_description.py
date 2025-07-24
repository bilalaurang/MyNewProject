def generate_car_description(make, model, year, price, kilometers, color, body_condition, 
                             mechanical_condition, engine_size, horsepower, top_speed):
    description = (
        f"The {year} {make} {model} sports car features a sleek {color} design, powered by a "
        f"{engine_size} gasoline engine delivering {horsepower} horsepower and a {top_speed} km/h top speed. "
        f"With only {kilometers} km, it’s in {body_condition} condition with {mechanical_condition} mechanics, "
        f"appealing to enthusiasts at ${price}. This vehicle combines style and performance, making it a "
        f"favorite among car lovers. This concludes the description."
    )
    words = description.split()
    if 50 <= len(words) <= 100:
        return description
    else:
        return f"Description out of range. Word count: {len(words)}. Text: {description}"

if __name__ == "__main__":
    sample_desc = generate_car_description(
        "Toyota", "Supra", 2003, 8000, 20000, "black", "good", "best", "3.0L", 500, 150
    )
    print(sample_desc)