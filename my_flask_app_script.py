import subprocess

from flask import Flask, request, jsonify
import requests
#%%
app = Flask(__name__)
#%%

#  tell Flask what URL should trigger our function
@app.route('/start-conversation', methods=['POST'])
def start_conversation():
    data = request.json
    reply = 'Ripsota Python'
    print(f'Response from Python: {reply}')  # Aggiungi questa linea per debug
    return jsonify({'reply': reply})

