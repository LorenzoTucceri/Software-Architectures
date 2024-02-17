import re
import sys
from config import Configuration

class Priority:
    def __init__(self):
        self.configuration = Configuration()

    def parse_expression(self, expression):
        # precedenza operatori da mettere in config
        precedence = {'OR': 1, 'AND': 2}
        # spazi per and/or
        expression = re.sub(r'([()])', r' \1 ', expression)
        expression = re.sub(r'(AND|OR)', r' \1 ', expression)

        # tokenizzo
        tokens = expression.split()

        # stack per tenere traccia degli operandi
        stack = []
        output = []

        for token in tokens:
            if token == '(':
                stack.append(token)
            elif token == ')':
                while stack and stack[-1] != '(':
                    output.append(stack.pop())
                stack.pop()  # Rimuovi la parentesi aperta
            elif token in {'AND', 'OR'}:
                while stack and stack[-1] in {'AND', 'OR'} and precedence[stack[-1]] >= precedence[token]:
                    output.append(stack.pop())
                stack.append(token)
            else:
                output.append(token)

        # Sposta gli operatori rimanenti nella coda di output
        while stack:
            output.append(stack.pop())

        return output

    def parse_priority_expression(self, expression):
        matches = re.findall(r"'([^']+)'|AND|OR", expression)

        # prendo solo i ms esistenti (filtro)
        services_in_expression = [match for match in matches if match in self.configuration.priority_relations]

        # in funzione della lista sopra creo la mappa di priorità dei ms
        priority_map = {service: self.configuration.priority_relations.get(service, 0) for service in services_in_expression}

        # assegno +1 ai ms duplicati
        for i, service in enumerate(services_in_expression):
            occurrences = services_in_expression.count(service)  # numero di ms duplicati
            if occurrences > 1:
                priority_map.pop(service,
                                 None)  # evito che riscriva il microservizio originale visto che ci troviamo nel caso dei ms duplicati

                for i in range(occurrences):
                    priority_map[service + "_" + str(i + 1)] = self.configuration.priority_relations.get(service, 0)
            else:
                # in questo caso setto la priorità originale
                priority_map[service] = self.configuration.priority_relations.get(service, 0)

        # ricalcolo le priorità in modo che partano da 0 (visto che sulla mappa vanno da 1 a 4 ma posso non avere ms che partono da 3
        scaled_priority_map = [(service, priority - min(
            priority for service, priority in sorted(priority_map.items(), key=lambda x: x[1])) + 1) for service,
                               priority in sorted(priority_map.items(), key=lambda x: x[1])]

        return scaled_priority_map

# Esempio di utilizzo della classe ExpressionParser
"""
if __name__ == "__main__":
    parser = ExpressionParser()

    expression = sys.argv[1]
    parsed_expression = parser.parse_expression(expression)
    priority_map = parser.parse_priority_expression(expression)

    print("Parsed Expression:")
    print(parsed_expression)

    print("\nPriority Map:")
    for service, priority in priority_map:
        print(service, priority)
"""