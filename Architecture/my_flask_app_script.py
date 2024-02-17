import subprocess
from tokenizer import RequestProcessor
from model import MiLA4UAssistant
from flask import Flask, request, jsonify
from config import Configuration

# %%
app = Flask(__name__)
# %%

# instances
config = Configuration()
tokenizer = RequestProcessor(config)
assistant = MiLA4UAssistant(config)
#ms = config.t_f

db = config.connect_to_database()


def tokenize_text(text):
    return tokenizer.reconstruct_sentence(tokenizer.process_request(text))


def parsing_text(text):
    input_data = f"{text}"#-{ms}

    with subprocess.Popen(["python", config.parser_path], stdin=subprocess.PIPE, stdout=subprocess.PIPE,
                          text=True) as process:
        output, error = process.communicate(input_data)

    return output, error

def get_parsed_expression():
    return

# https://stackoverflow.com/questions/77550506/what-is-the-right-way-to-do-system-prompting-with-ollama-in-langchain-using-pyth
@app.route('/start-conversation', methods=['POST'])
def start_conversation():
    data = request.json
    print("ricevuto", data)
    data = tokenize_text(data)
    print("toenizzato", data)
    parsed = parsing_text(data)

    if parsed[0].split("\n")[0] == "0":
        stdout = "modello" + "\t" #+ pa
        #stdout = assistant.respond(data)
    else:
        stdout = "GUser :== " + data

    print(f'Response from Python: {stdout}')  # Aggiungi questa linea per debug
    return jsonify({'reply': stdout})
