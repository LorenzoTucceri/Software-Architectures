import subprocess
import sys

from langchain import LLMChain
#from langchain.callbacks.manager import CallbackManager
#from langchain.callbacks.streaming_stdout import StreamingStdOutCallbackHandler
import warnings
warnings.filterwarnings("ignore")
from langchain.llms import Ollama
from langchain.prompts import (
    ChatPromptTemplate,
    MessagesPlaceholder,
    HumanMessagePromptTemplate,
)

class MiLA4UAssistant:
    def __init__(self, configuration, system_template=None):
        self.template = system_template or (
            "You operate as MiLA4U, a virtual assistant specialized in the context of the NdR Street Science Fair, "
            "an event organized by the University of L'Aquila. Your expertise lies in microservices for space "
            "booking, management, navigation, etc. When responding, answer succinctly and consider that If the query "
            "goes beyond the specified context, politely indicate only that it's outside your expertise as an assistant"
            " of that specified context."
        )

        self.llm = Ollama(
            base_url=configuration.base_url_OLLAMA,
            model=configuration.model_name,
            system=self.template,
            # callback_manager=CallbackManager([StreamingStdOutCallbackHandler()])
        )

        self.prompt = ChatPromptTemplate(
            messages=[
                HumanMessagePromptTemplate.from_template("{user_input}" + "\n" + "MiLA4U : "),
            ]
        )

        self.conversation = LLMChain(
            prompt=self.prompt,
            llm=self.llm,
            verbose=0,
        )

    def respond(self, user_input):
        return self.conversation.run(user_input)

"""
if __name__ == "__main__":
    assistant = MiLA4UAssistant()
    user_input = "Hello, what services do you provide?"
    response = assistant.respond(user_input)
    print(response)
"""


