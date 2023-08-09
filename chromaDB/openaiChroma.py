from langchain.vectorstores import Chroma
from langchain.embeddings import OpenAIEmbeddings
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.llms import OpenAI
from langchain.chains import VectorDBQA
from langchain.document_loaders import TextLoader
from typing import List
from langchain.schema import Document
from langchain.document_loaders import PyPDFDirectoryLoader
import os
# importing required modules
from PyPDF2 import PdfReader

os.environ['OPENAI_API_KEY'] = "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"

class Genie:

    def __init__(self):
        # self.file_path = file_path
       
        # self.loader = PyPDFDirectoryLoader("pdf/")
        # self.documents = self.loader.load()

  
        # creating a pdf reader object
        path = os.path.abspath("chromaDB/emppdf.pdf")
        reader = PdfReader(path)
        
        # printing number of pages in pdf file
        print(len(reader.pages))
    
        # getting a specific page from the pdf file
        pathtxt = os.path.abspath("chromaDB/test.txt")
        for idx, x in enumerate(reader.pages):
            # page = reader.pages[idx]
            # extracting text from page
            text = x.extract_text()
            print(text)
            f = open(pathtxt, "a+", encoding="utf8")
            f.write(text)
            f.close()
       
        # pathtxt = os.path.abspath("chromaDB/test.txt")       
        self.loader = TextLoader(pathtxt, encoding="utf8")
        self.documents = self.loader.load()
      
        self.texts = self.text_split(self.documents)
        self.vectordb = self.embeddings(self.texts)
        self.genie = VectorDBQA.from_chain_type(llm=OpenAI(), chain_type="stuff", vectorstore=self.vectordb)

    @staticmethod
    def text_split(documents: TextLoader):
        text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=0)
        texts = text_splitter.split_documents(documents)
        return texts

    @staticmethod
    def embeddings(texts: List[Document]):
        embeddings = OpenAIEmbeddings()
        vectordb = Chroma.from_documents(texts, embeddings)
        return vectordb

    def ask(self, query: str):
        return self.genie.run(query)


if __name__ == "__main__":
    genie = Genie()
    print(genie.ask("How many sick days are we allowded?"))