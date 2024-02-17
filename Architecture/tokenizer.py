import re

class RequestProcessor:
    def __init__(self, configuration):
        self.list_ms = configuration.keys_to_include
        self.synonyms = configuration.sinonimi

    def process_request(self, request):
        tokens = []
        for word in re.findall(r'\w+|\S+', request):
            for sinonimo in self.synonyms:
                if word.lower() in self.synonyms[sinonimo]:
                    tokens.append(sinonimo)
                    break
            else:
                tokens.append(word)

        return tokens

    def find_matching_service(self, tokens):
        finded = []
        for token in tokens:
            if token in self.list_ms:
                finded.append(token)
        return finded

    def reconstruct_sentence(self, tokens):
        stack = []
        case = 0
        mservices_in_tokens = self.find_matching_service(tokens)
        while tokens:
            token = tokens.pop(0)

            if len(mservices_in_tokens) == 1 and token == mservices_in_tokens[0] and (case == 1 or case == 0):
                options = []
                case = 1
                options.append("'" + mservices_in_tokens[0] + "'")
                stack.append(f"{''.join(options)}")

            elif (token == "seq" or token == "one_of"):
                options = []
                while tokens:
                    if tokens[0] in {"and", "or", "?"} or tokens[0] not in self.list_ms:
                        tokens.pop(0)
                    else:
                        options.append("'" + tokens.pop(0) + "'")

                if token == 'one_of':
                    stack.append(f"one_of[{';'.join(options)}]")
                else:
                    stack.append(f"seq[{';'.join(options)}]")

            else:
                options = []
                while tokens:
                    if tokens[0] in self.list_ms or tokens[0] in {"and", "or"}:
                        if tokens[0] in self.list_ms:
                            options.append("'" + tokens.pop(0) + "'")
                        else:
                            options.append(tokens.pop(0))
                    else:
                        tokens.pop(0)
                stack.append(f"[{''.join(options)}]")

        return stack[0]

# Esempio di utilizzo della classe RequestProcessor
"""
if __name__ == "__main__":
    processor = RequestProcessor()

    request = "Check the weather and book a spot"
    tokens = processor.process_request(request)
    reconstructed_sentence = processor.reconstruct_sentence(tokens)

    print(f"Original Request: {request}")
    print(f"Reconstructed Sentence: {reconstructed_sentence}")
"""
