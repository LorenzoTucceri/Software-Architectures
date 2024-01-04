keys_to_include = ['event_booking', 'weather_checking', 'ticket_availability', 'parking_recommendation']

sinonimi = {
    'event_booking': ['reservation', 'booking', 'book a spot', 'reserve a ticket', 'event pass', 'event'],
    'weather_checking': ['check weather', 'weather forecast', 'weather updates', 'climate check',
                         'weather information', 'weather'],
    'ticket_availability': ['ticket availability', 'ticket status', 'ticket access', 'availability check',
                            'available tickets'],
    'parking_recommendation': ['parking advice', 'parking suggestions', 'recommend parking', 'parking options',
                               'best parking', 'parking'],
    'or': ['or', 'otherwise', 'alternatively', 'or else', 'oppositely', 'in case otherwise', 'either', 'else',
           'otherwise'],
    'and': ['and', 'also', 'in addition', 'furthermore', 'moreover', 'in conjunction with', 'based'],
    'one_of': ['one', 'either', 'either one', 'any one', 'choose one', 'select one', 'pick one'],
    'seq': ['sequences', 'sequence', 'ordered set', 'series', 'sequential', 'succession', 'chain']
}

priority_relations = {
    'ticket_availability': 1,
    'event_booking': 2,
    'weather_checking': 3,
    'parking_recommendation': 4
}

# fare script che modifichi questo file in formato testo che aggiunga sia il nuovo microservizio a keys_to_include
# che alla mappa dei sinonimi
f = [key for key in keys_to_include if key in sinonimi]
t_f = r'\'{}\''.format(r'\' | \''.join(keys_to_include))

# for init priority table : priority.py
table = f"{'Service':<30}{'Priority':<10}\n{'-' * 40}"
