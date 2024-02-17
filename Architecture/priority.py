import re


class Priority:
    def __init__(self, configuration):
        self.configuration = configuration

    def parse_priority_expression(self, expression):
        expression = str(expression)

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
            priority for service, priority in sorted(priority_map.items(), key=lambda x: x[1])) + 1) for
                               service, priority
                               in sorted(priority_map.items(), key=lambda x: x[1])]

        return scaled_priority_map