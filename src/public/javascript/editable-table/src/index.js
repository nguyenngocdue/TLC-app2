import { TableRow } from './TableRow'

export const EditableTable = (params) => {
    console.log(params)

    let table = "<table>"

    table += TableRow()

    table += "</table>"

    return table
}

// export default EditableTable