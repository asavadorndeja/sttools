from docx import Document

document = Document()
paragraph = document.add_paragraph('Lorem ipsum dolor sit amet.')
document.save('test.docx')
