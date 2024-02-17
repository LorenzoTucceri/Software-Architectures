import sys
import ply.lex as lex
import ply.yacc as yacc
from priority import Priority
from config import Configuration

config = Configuration()
ms = config.t_f
user_input = sys.stdin.read()


# token
tokens = [
    'ONEOF',
    'SEQ',
    'AND',
    'OR',
    'f',  # per gestire le stringhe terminali
    'LPARENT',  # per gestire la [
    'RPARENT',  # per gestire la ]
    'SEMICOLON'  # per gestire ;
]

"""
prec ‘ticket_availability’ > ((‘event_booking’ ⪰ ‘parking_recommendation’) | ‘weather_checking’)
"""
# regole dei token
t_ONEOF = r'one_of'
t_SEQ = r'seq'
t_AND = r'and'
t_OR = r'or'
t_LPARENT = r'\['
t_RPARENT = r'\]'
t_SEMICOLON = r'\;'
t_f = ms

t_ignore = ' \t'  # per ignorare gli spazi

# regole di precedenza
precedence = (
    ('left', 'OR'),
    ('left', 'AND'),
)


# per errori dei token
def t_error(t):
    print(f"Token non riconosciuto: {t.value[0]}")
    t.lexer.skip(1)


def add_parentheses(tokens):
    new_tokens = []
    i = 0
    while i < len(tokens):
        if tokens[i] == 'and':
            new_tokens[-1] = '(' + new_tokens[-1]  # inizio and
            i += 1  # salto l'and
            new_tokens[-1] = new_tokens[-1] + "and"
            while i < len(tokens) and tokens[i] != 'or':
                new_tokens.append(tokens[i])
                i += 1
            new_tokens[-1] += ')'  # fine and
        else:
            new_tokens.append(tokens[i])
            i += 1

    return new_tokens


# regole parsing
def p_start(p):
    '''
    G_user : F1
           | f
    '''


def p_F1(p):
    '''
    F1 : LPARENT F2 RPARENT
       | ONEOF LPARENT F3 RPARENT
       | SEQ LPARENT F3 RPARENT
    '''


def p_F2(p):
    '''
    F2 : F2 AND F2
       | F2 OR F2
       | f
    '''
    """
    global reconstruted

    if len(p) == 2:
        p[0] = [p[1]]  # 1 elemento restituisco lista
    else:
        p[0] = p[1] + [p[2]] + p[3]  # concateno le liste

    p[0] = add_parentheses(p[0]) # aggiungo le parentesi

    reconstruted = p[0]

    """
def p_F3(p):
    '''
    F3 : f SEMICOLON F3
       | f
    '''
    p[0] = p[1]


# per errori parsing
def p_error(p):
    print("0")  # Errore di parsing inatteso a livello di token: {p}


lexer = lex.lex()
parser = yacc.yacc()

# user_input = sys.argv[1]

result = parser.parse(user_input, lexer=lexer)

#print("RESULT", result)
#concatenated_string = ''.join(reconstruted)
#print(reconstruted)
# dal parsing poi rimuovo gli apici doppi
#cleaned_string = concatenated_string.replace("''", "'")
#print("reeee",user_input)

print("1")

"""
result = parser.parse(user_input, lexer=lexer)
print("RESULT", result)
    #concatenated_string = ''.join(reconstruted)
        # dal parsing poi rimuovo gli apici doppi
    #cleaned_string = concatenated_string.replace("''", "'")
print("reeee",user_input)



----
priority = Priority()

parsed_expression = priority.parse_expression(user_input)
priority_map = priority.parse_priority_expression(user_input)

print("Parsed Expression:")
print(parsed_expression)
"""

# script_path = "my_flask_app_script.py"
# subprocess.run(["python", script_path, parsed_expression]) # result

# print(f"Errore durante il parsing: {e}")

"""
examples = [
    "GUser ::= one_of['weather_checking';'parking_recommendation']",
    "GUser ::= seq['parking_recommendation'; 'event_booking']",
    "GUser ::= ['ticket_availability' and 'event_booking']",
    ['ticket_avaiability' OR 'event_booking' AND 'parking_recommendation']
    'ticket_availability'
]
"""
