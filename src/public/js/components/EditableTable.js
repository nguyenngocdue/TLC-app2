const editableColumns = {}, tableObject = {}
const addANewLine = (params) => {
    const { tableId, columns, showNo, showNoR } = params
    console.log("ADD LINE TO", params)
    const table = document.getElementById(tableId)
    const row = table.insertRow()
    if (showNo) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "No."
    }
    columns.forEach((column) => {
        let renderer = 'newCell'
        const cell = row.insertCell();
        cell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        const name = tableId + "[" + column['dataIndex'] + "][]"
        switch (column['renderer']) {
            case 'read-only-text':
                if (column['dataIndex'] === 'id') {
                    renderer = "<input name=" + name + " type='hidden' />NEW"
                } else {
                    renderer = 'New read-only-text'
                }
                break
            case 'dropdown':
                if (column['dataIndex'] === 'status') {
                    renderer = "<select name='" + name + "' class='" + column['classList'] + "'>"
                    column['cbbDataSource'].forEach((status) => {
                        statusObject = column['cbbDataSourceObject'][status]
                        renderer += "<option value='" + status + "'>" + statusObject.title + "</option>"
                    })
                    renderer += "</select>"
                } else {
                    renderer = "<select name='" + name + "' class='" + column['classList'] + "'></select>"
                }
                break
            case "text":
                renderer = "<input name='" + name + "' class='" + column['classList'] + "' />";
                break
            case "textarea":
                renderer = "<textarea name='" + name + "' class='" + column['classList'] + "'></textarea>"
                break
            default:
                renderer = "Unknown how to render " + column['renderer']
                break
        }
        console.log("Insert column", column['dataIndex'], renderer)
        cell.innerHTML = renderer;
    })
    if (showNoR) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "No."
    }
}