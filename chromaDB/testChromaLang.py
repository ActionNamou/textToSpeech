# import
from langchain.embeddings.sentence_transformer import SentenceTransformerEmbeddings
from langchain.text_splitter import CharacterTextSplitter
from langchain.vectorstores import Chroma
from langchain.document_loaders import TextLoader


from langchain.document_loaders import PyPDFLoader

loader = PyPDFLoader("./emppdf.pdf")
documents = loader.load_and_split()
# load the document and split it into chunks
# loader = TextLoader("state_of_the_union.txt")
# documents = loader.load()

# split it into chunks
text_splitter = CharacterTextSplitter(chunk_size=1000, chunk_overlap=0)
docs = text_splitter.split_documents(documents)

# create the open-source embedding function
embedding_function = SentenceTransformerEmbeddings(model_name="all-MiniLM-L6-v2")

# load it into Chroma
db = Chroma.from_documents(docs, embedding_function)

# query it
query = "How many days do we have for sick leave"
docs = db.similarity_search(query)

# print results
print(docs[0].page_content)