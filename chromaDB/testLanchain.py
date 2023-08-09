# from langchain.llms import OpenAI
# from langchain.chat_models import ChatOpenAI

# llm = OpenAI()
# chat_model = ChatOpenAI()
from langchain.document_loaders import DirectoryLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
import os
from langchain.embeddings import SentenceTransformerEmbeddings
from langchain.vectorstores import Chroma
directory = os.path.abspath("chromaDB/txtFiles")

def load_docs(directory):
  loader = DirectoryLoader(directory)
  documents = loader.load()
  return documents

documents = load_docs(directory)
len(documents)



def split_docs(documents,chunk_size=1000,chunk_overlap=20):
  text_splitter = RecursiveCharacterTextSplitter(chunk_size=chunk_size, chunk_overlap=chunk_overlap)
  docs = text_splitter.split_documents(documents)
  return docs

docs = split_docs(documents)
print(len(docs))
# print(docs)


embeddings = SentenceTransformerEmbeddings(model_name="all-MiniLM-L6-v2")


db = Chroma.from_documents(docs, embeddings)


query = "two week notice"
matching_docs = db.similarity_search(query)

for match in matching_docs:
  print(match)


# print(matching_docs[0])
# print(matching_docs[0]['page_content'])