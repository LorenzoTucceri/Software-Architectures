# import re
import nltk


class RequestProcessor:
    def __init__(self, configuration):
        """
            Initializes a RequestProcessor object with the keys to include, synonyms, and a variable for succession.

            :param configuration: Configuration object containing relevant information.
        """
        self.list_ms = configuration.keys_to_include
        self.synonyms = configuration.sinonimi
        self.succ = None

    """
    def similar(self, a, b):
        return SequenceMatcher(None, a, b).ratio()
    """

    def process_request(self, request):
        """
            Processes a user request, tokenizing it and replacing words with their synonyms if available.

            :param request: User request to process.
            :return: List of processed tokens.
        """
        tokens = []

        for word in nltk.word_tokenize(request):  # re.findall(r'\w+|\S+', request):
            for sinonimo in self.synonyms:
                if word.lower() in self.synonyms[sinonimo]:
                    tokens.append(sinonimo)
                    break
            else:
                tokens.append(word)
        # print("tokens",tokens)
        self.check_special_chars(tokens)
        return tokens

    def find_matching_service(self, tokens):
        """
            Finds microservices that match tokens.

            :param tokens: List of tokens to search for microservices.
            :return: List of matching microservices.
        """
        finded = []
        for token in tokens:
            if token in self.list_ms:
                finded.append(token)

        return finded

    def check_special_chars(self, lista):
        """
            Checks for special characters in the token list and sets the 'succ' variable accordingly.

            :param lista: List of tokens.
        """
        lunghezza_lista = len(lista)
        caratteri_speciali = ['seq', 'one_of']  # Sostituisci con i tuoi caratteri speciali

        for i in range(lunghezza_lista - 1):
            elemento_corrente = lista[i]
            elemento_successivo = lista[i + 1]

            if elemento_corrente in caratteri_speciali or elemento_successivo in caratteri_speciali:
                self.succ = True
                return

        self.succ = False

    def reconstruct_sentence(self, tokens):
        """
            Reconstructs a sentence based on the given tokens.

            :param tokens: List of tokens.
            :return: Reconstructed sentence.
        """
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
                if self.succ is False:
                    while tokens:
                        if tokens[0] in self.list_ms or tokens[0] in {"and", "or"}:
                            if tokens[0] in self.list_ms:
                                options.append("'" + tokens.pop(0) + "'")
                            else:
                                options.append(tokens.pop(0))
                        else:
                            tokens.pop(0)
                    stack.append(f"[{''.join(options)}]")
        # print("stack", stack)
        return stack[0]
