keys_to_include = ['event_booking', 'weather_checking', 'ticket_availability', 'parking_recommendation']

sinonimi = {
    'event_booking': ['reservation', 'event'],
    'weather_checking': ['check', 'weather'],
    'ticket_availability': ['availability', 'tickets'],
    'parking_recommendation': ['recommendation', 'parking'],
    'or': ['or', 'otherwise', 'alternatively', 'or else', 'oppositely', 'in case otherwise'],
    'and': ['and'],
    'one_of': ['one'],
    'seq': ['sequences']
}

# fare script che modifichi questo file in formato testo che aggiunga sia il nuovo microservizio a keys_to_include che alla mappa dei sinonimi
f = [key for key in keys_to_include if key in sinonimi]
t_f = r'\'{}\''.format(r'\' | \''.join(keys_to_include))
