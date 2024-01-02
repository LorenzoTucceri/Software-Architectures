import re
import sys
from config import f as services

def parse_priority_expression(expression):
    # Estrai i servizi e gli operatori dalla stringa
    matches = re.findall(r"'([^']+)'|AND|OR", expression)

    # Filtra solo i servizi
    services_in_expression = [match for match in matches if match in services]

    # Costruisci la mappa delle priorità solo per i servizi presenti nella stringa
    priority_map = {service: i + 1 for i, service in enumerate(services_in_expression)}

    # Ordina la mappa delle priorità in base alla posizione nella stringa
    sorted_priority_map = sorted(priority_map.items(), key=lambda x: services_in_expression.index(x[0]))

    return sorted_priority_map

print()
print("--- Priority Expression ---")
expression = sys.argv[1]
print(f"\nEspressione originale: {expression}\n")
priority_map = parse_priority_expression(expression)

table = f"{'Service':<30}{'Priority':<10}\n{'-' * 40}"
for service, priority in priority_map:
    table += f"\n{service:<30}{priority:<10}"

print(table + '\n')
#print(f"Espressione ricostruita: {reconstructed_expression}")
