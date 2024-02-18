import re


class Priority:
    def __init__(self, configuration):
        """
            Initializes a Priority object with the provided configuration.

            :param configuration: Configuration object containing relevant information.
        """
        self.configuration = configuration

    def parse_priority_expression(self, expression):
        """
            Parses a priority expression to create a priority map for microservices.

            :param expression: Priority expression to parse.
            :return: List of tuples containing microservices and their scaled priorities.
        """
        expression = str(expression)

        matches = re.findall(r"'([^']+)'|AND|OR", expression)

        # Take only the existing microservices (filter).
        services_in_expression = [match for match in matches if match in self.configuration.priority_relations]

        # Based on the list above, create the priority map for microservices.
        priority_map = {service: self.configuration.priority_relations.get(service, 0) for service in services_in_expression}

        # Assign +1 to duplicate microservices.
        for i, service in enumerate(services_in_expression):
            occurrences = services_in_expression.count(service)  # numero di ms duplicati
            if occurrences > 1:
                priority_map.pop(service,
                                 None)  # Avoid rewriting the original microservice since we are in the case of duplicate microservices.

                for i in range(occurrences):
                    priority_map[service + "_" + str(i + 1)] = self.configuration.priority_relations.get(service, 0)
            else:
                # in questo caso setto la priorità originale
                priority_map[service] = self.configuration.priority_relations.get(service, 0)
        # Recalculate the priorities so that they start from 0 (since on the map they range from 1 to n, but I may not have microservices starting from 3)
        scaled_priority_map = [(service, priority - min(
            priority for service, priority in sorted(priority_map.items(), key=lambda x: x[1])) + 1) for
                               service, priority
                               in sorted(priority_map.items(), key=lambda x: x[1])]

        return scaled_priority_map