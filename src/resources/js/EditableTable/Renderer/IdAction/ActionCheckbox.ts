import { LengthAware, TableCellType } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

declare let tableData: { [tableName: string]: LengthAware }

export class ActionCheckbox extends Renderer4View {
    protected divClass: string = 'mx-auto'
    protected divStyle: { [key: string]: string } = { width: '30px' }
    protected tdClass: string = 'text-center'

    updateMasterCheckbox(): void {
        const dataSource = tableData[this.tableName]
        const masterCheckbox = document.getElementById(
            `${this.tableName}__checkbox_all`,
        ) as HTMLInputElement
        if (masterCheckbox === null) {
            return
        }
        const checkedCount = dataSource.data.filter((row) => row.__checked__ !== undefined).length
        if (checkedCount === 0) {
            masterCheckbox.checked = false
            masterCheckbox.indeterminate = false
        } else if (checkedCount === dataSource.data.length) {
            masterCheckbox.checked = true
            masterCheckbox.indeterminate = false
        } else {
            masterCheckbox.checked = false
            masterCheckbox.indeterminate = true
        }
    }
    updateDataSource(): void {
        const dataSource = tableData[this.tableName]
        if (dataSource.data[this.rowIndex].__checked__ === undefined) {
            dataSource.data[this.rowIndex].__checked__ = true as unknown as TableCellType
        } else {
            delete dataSource.data[this.rowIndex].__checked__
        }
    }

    applyOnChangeScript(): void {
        //add event listener
        const { tableName, rowIndex } = this
        const checkbox = document.getElementById(
            `${tableName}__cb__${rowIndex}`,
        ) as HTMLInputElement
        // console.log('checkbox', checkbox)
        checkbox.addEventListener('change', () => {
            this.updateDataSource()
            this.updateMasterCheckbox()
        })
    }

    control() {
        const { tableName, rowIndex } = this

        return `<input id="${tableName}__cb__${rowIndex}" type="checkbox" />`
    }
}
