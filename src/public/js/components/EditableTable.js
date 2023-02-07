const editableColumns = {}
const addANewLine = ({ tableId, columns }) => {
    console.log("ADD LINE TO", tableId, columns)
    const table = document.getElementById(tableId)
    const row = table.insertRow()
    columns.forEach((column) => {
        const cell = row.insertCell();
        cell.innerHTML = column['renderer']
    })
}