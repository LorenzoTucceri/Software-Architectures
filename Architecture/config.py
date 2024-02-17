import mysql.connector

class Configuration:
    def __init__(self):
        self.host = "localhost"
        self.database_name = "MiLA4U"
        self.database_user = "mila4u"
        self.password = "7Cx.VWwuRn2CB5)u"

        self.mydb = None
        self.connect_to_database()

        self.keys_to_include = None  # ['event_booking', 'weather_checking', 'ticket_availability', 'parking_recommendation']
        self.get_microservices()

        self.keys_ = ["and", "or", "sequential", "one_of"] + self.keys_to_include

        self.sinonimi = {
            'event_booking': ['reservation', 'booking', 'book a spot', 'event pass', 'event'],
            'weather_checking': ['check weather', 'weather forecast', 'weather updates', 'climate check',
                                 'weather information', 'weather', 'rainy', 'rain', 'sunny', 'sun', 'cloudy', 'cloud', 'foggy', 'fog', 'snowy', 'snow'],
            'ticket_availability': ['ticket availability', 'ticket status', 'ticket access', 'availability check',
                                    'available tickets', 'ticket', 'reserve a ticket'],
            'parking_recommendation': ['parking advice', 'parking suggestions', 'recommend parking', 'parking options',
                                       'best parking', 'parking', 'park'],
            'or': ['or', 'otherwise', 'or else', 'oppositely', 'in case otherwise', 'either', 'else',
                   'otherwise'],
            'and': ['and', 'also', 'in addition', 'furthermore', 'moreover', 'in conjunction with', 'based', 'for', 'while'],
            'one_of': ['one', 'either', 'either one', 'any one', 'choose one', 'select one'],
            'seq': ['if', 'sequences', 'sequence', 'ordered set', 'series', 'sequential', 'sequentially', 'succession',
                    'chain', 'given']
        }

        self.f = [key for key in self.keys_to_include if key in self.sinonimi]
        self.t_f = r'\'{}\''.format(r'\' | \''.join(self.keys_to_include))
        """
        self.priority_relations = {
            'ticket_availability': 1,
            'event_booking': 2,
            'weather_checking': 3,
            'parking_recommendation': 4
        }
        """
        self.priority_relations = None
        self.get_priorities()

        self.base_url_OLLAMA = "http://localhost:11434"
        self.model_name = "llama2"

        self.parser_path = "Software-Architectures/Architecture/grammatica_iniziale.py"


    def get_microservices(self):
        keys_to_include = []
        cursor = self.mydb.cursor(dictionary=True)
        try:
            cursor = self.mydb.cursor(dictionary=True)
            cursor.execute("SELECT name FROM microservices")

            for row in cursor.fetchall():
                keys_to_include.append(row['name'])

        except mysql.connector.Error as err:
            print(f"Errore MySQL: {err}")

        finally:
            cursor.close()

        self.keys_to_include = keys_to_include

    def get_priorities(self):
        priority_relations = {}
        cursor = self.mydb.cursor(dictionary=True)
        try:
            #scursor = self.mydb.cursor(dictionary=True)
            cursor.execute("SELECT name, priority FROM microservices")

            for row in cursor.fetchall():
                priority_relations[row['name']] = row['priority']

        except mysql.connector.Error as err:
            print(f"Errore MySQL: {err}")

        finally:

            cursor.close()
                #self.mydb.close()
        self.priority_relations = priority_relations

    def connect_to_database(self):
        try:
            self.mydb = mysql.connector.connect(
                host=self.host,
                database=self.database_name,
                user=self.database_user,
                password=self.password
            )
        except mysql.connector.errors.InterfaceError:
            print("Unable to connect to the database")

    def update_keys(self, new_service, new_synonyms):
        if new_service not in self.keys_to_include:
            self.keys_to_include.append(new_service)
            self.sinonimi[new_service] = new_synonyms

    def initialize_priority_table(self):
        table = f"{'Service':<30}{'Priority':<10}\n{'-' * 40}"
        for service, priority in self.priority_relations.items():
            table += f"\n{service:<30}{priority:<10}"
        return table


"""
config = Configuration()
config.connect_to_database("localhost", "MiLA4U", "mila4u", "7Cx.VWwmuRn2CB5)u")
config.update_keys("new_service", ["new", "service", "additional"])
priority_table = config.initialize_priority_table()
print(priority_table)
"""