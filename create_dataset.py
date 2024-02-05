import random

def generate_strings():
    services = ['event_booking', 'weather_checking', 'ticket_availability', 'parking_recommendation']
    max_repeats = 2
    service_counts = {}

    def generate_F1(repeat_count, service_counts):
        if repeat_count >= max_repeats or any(count >= max_repeats for count in service_counts.values()):
            return generate_f(service_counts)

        if random.choice([True, False]):
            result = generate_F2(repeat_count, service_counts)
            if isinstance(result, list):
                return f"[{result[0]} OR {result[1]}]"
            else:
                return result
        else:
            choice = random.randint(0, 1)
            return generate_ONEOF_F3(service_counts.copy()) if choice == 0 else generate_SEQ_F3(
                service_counts.copy())

    def generate_F2(repeat_count, service_counts):
        if random.choice([True, False]):
            service = random.choice(services)
            return generate_f(service_counts, service)
        else:
            left = generate_F2(repeat_count, service_counts.copy())
            right = generate_F2(repeat_count, service_counts.copy())

            if isinstance(left, str) and isinstance(right, str):
                return f"[{left} AND {right}]" if random.randint(0, 1) else f"[{left} OR {right}]"
            else:
                return f"{left} AND {right}" if random.randint(0, 1) else f"{left} OR {right}"

    def generate_ONEOF_F3(service_counts):
        selected_services = [generate_f(service_counts.copy()) for _ in range(2)]
        return f"ONEOF[{'; '.join(selected_services)}]"

    def generate_SEQ_F3(service_counts):
        selected_services = [generate_f(service_counts.copy()) for _ in range(2)]
        return f"SEQ[{'; '.join(selected_services)}]"

    def generate_F3(repeat_count, service_counts):
        if repeat_count >= max_repeats or any(count >= max_repeats for count in service_counts.values()):
            return generate_f(service_counts)
        if random.choice([True, False]):
            return generate_f(service_counts)
        else:
            next_f = generate_f(service_counts)
            return f"{next_f}; {generate_F3(repeat_count + 1, service_counts.copy())}" if random.randint(0, 1) else next_f

    def generate_f(service_counts, service=None):
        if service is None:
            service = random.choice(services)
        service_counts.setdefault(service, 0)
        if service_counts[service] >= max_repeats:
            return generate_f(service_counts)
        service_counts[service] += 1
        return service

    all_strings = set()

    def generate_unique_string():
        string = generate_G_user(0, service_counts.copy())
        while string in all_strings:
            string = generate_G_user(0, service_counts.copy())
        all_strings.add(string)
        return string

    def generate_G_user(repeat_count, service_counts):
        if repeat_count >= max_repeats or any(count >= max_repeats for count in service_counts.values()):
            return generate_f(service_counts)
        return generate_F1(repeat_count + 1, service_counts.copy()) if random.choice([True, False]) else generate_f(service_counts)

    num_strings = 200
    file_path = 'output.txt'

    def write_to_file(input_string, file_path):
        with open(file_path, 'a+') as file:
            file.seek(0)
            existing_strings = file.readlines()

            if input_string + '\n' not in existing_strings:
                file.write(input_string + '\n')

    i = 1
    while i <= num_strings:
        input_string = generate_unique_string()

        count_and = input_string.count('AND')
        count_or = input_string.count('OR')

        if count_or < 2 and count_and < 2:
            write_to_file(input_string, file_path)
            i += 1

generate_strings()
