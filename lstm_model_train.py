import re
import subprocess
import sys
from config import f as list_microservices
from config import sinonimi


def process_request(request):
    tokens = []
    for word in re.findall(r'\w+|\S+', request):
        for sinonimo in sinonimi:
            if word.lower() in sinonimi[sinonimo]:
                tokens.append(sinonimo)
                break
        else:
            tokens.append(word)

    return tokens


def find_matching_service(tokens):
    finded = []
    for token in tokens:
        if token in list_microservices:
            finded.append(token)
    return finded


def reconstruct_sentence(tokens):
    stack = []
    case = 0
    mservices_in_tokens = find_matching_service(tokens)
    while tokens:
        token = tokens.pop(0)

        if len(mservices_in_tokens) == 1 and token == mservices_in_tokens[0] and (case == 1 or case == 0):
            options = []
            case = 1
            options.append("'" + mservices_in_tokens[0] + "'")
            stack.append(f"{''.join(options)}")


        elif (token == "seq" or token == "one_of"):
            options = []
            while tokens:
                if tokens[0] in {"and", "or", "?"} or tokens[0] not in list_microservices:  # mettere anche caratteri
                    # speciali
                    tokens.pop(0)
                else:
                    options.append("'" + tokens.pop(0) + "'")

            if token == 'one_of':
                stack.append(f"one_of[{';'.join(options)}]")
            else:
                stack.append(f"seq[{';'.join(options)}]")

        else:
            options = []
            while tokens:
                if tokens[0] in list_microservices or tokens[0] in {"and", "or"}:
                    if tokens[0] in list_microservices:
                        options.append("'" + tokens.pop(0) + "'")
                    else:
                        options.append(tokens.pop(0))
                else:
                    tokens.pop(0)
            stack.append(f"[{''.join(options)}]")

    return stack[0]

#input_request = "Is there parking spot in zone? otherwise find me a parking spot based on weather conditions"  # sys.argv[1]  prendere da terminale
#input_request= "sequences parking and event"
input_request = "Is there parking spot in zone? otherwise find me a parking spot based on weather conditions or weather"
print("--- Processing input ---" + '\n')
print("Input string         :", input_request)
output = process_request(input_request)
print("Processed string     :", output)

output_sentence = reconstruct_sentence(output)
print("Reconstruced string  :", output_sentence)

script_path = "grammatica_iniziale.py"
subprocess.run(["python", script_path, output_sentence])

'''
grammatica:

G_user -> F1  |  f
F1 ->  [F2]  |  ONEOF [F3]  |  SEQ [F3]
F2 -> F2 AND F2  |  F2 OR F2  |  f
F3 -> f; F3  |  f
f -> 'event_booking'  |  'weather_checking'  |  'ticket_avaiability'  |  'parking_recommendation'
 
'''