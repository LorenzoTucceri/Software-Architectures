import subprocess
from tokenizer import RequestProcessor
from model import MiLA4UAssistant
from flask import Flask, request, jsonify
from config import Configuration
from priority import Priority
# %%
app = Flask(__name__)
# %%

# instances
config = Configuration()
tokenizer = RequestProcessor(config)
assistant = MiLA4UAssistant(config)

def tokenize_text(text):
    """
        Tokenizes the input text using the RequestProcessor's process_request method.

        :param text: Input text to tokenize.
        :return: Reconstructed sentence after tokenization.
    """
    return tokenizer.reconstruct_sentence(tokenizer.process_request(text))

def parsing_text(text):
    """
        Parses the input text using an external parser specified in the configuration.

        :param text: Input text to parse.
        :return: Tuple containing the parsed output and any errors.
    """
    input_data = f"{text}"#-{ms}

    with subprocess.Popen(["python", config.parser_path], stdin=subprocess.PIPE, stdout=subprocess.PIPE,
                          text=True) as process:
        output, error = process.communicate(input_data)

    return output, error


@app.route('/start-conversation', methods=['POST'])
def start_conversation():
    """
        Handles the start of a conversation by receiving user input, tokenizing it, parsing it, and generating a response.

        :return: JSON response containing the generated reply.
    """
    priority = Priority(config)

    data = request.json
    data = tokenize_text(data)

    parsed = parsing_text(data)

    if parsed[0].split("\n")[0] == "0":
        #stdout = "model"
        stdout = assistant.respond(data)
    else:
        priority_map = priority.parse_priority_expression(data)

        print("Parsed Map:")
        for service, priority in priority_map:
            config.table += f"\n{service:<30}{priority:<10}"

        print(config.table + '\n')
        stdout = "GUser :== " + data

    print(f'Response from Python: {stdout}')  
    return jsonify({'reply': stdout})
