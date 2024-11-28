import { twMerge } from 'tailwind-merge'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }

export class PerPage {
    private lengthAware: LengthAware
    private options = [10, 15, 20, 30, 40, 50, 100]
    private classList = ``

    constructor(params: TableParams) {
        const tableName = params.tableName
        const dataSource = tableData[tableName]
        this.lengthAware = dataSource as LengthAware
        this.classList = params.tableConfig.classList?.dropdown || ''
        // console.log(this.lengthAware)
    }

    render(): string {
        const { per_page } = this.lengthAware

        // Build the `select` element as a string
        const selectHtml = `
            <select class="${twMerge(this.classList, `w-30`)}">
                ${this.options
                    .map((option) => {
                        const selected = option === per_page ? 'selected' : ''
                        return `<option value="${option}" ${selected}>${option} / page</option>`
                    })
                    .join('')}
            </select>
        `

        return selectHtml.trim() // Trim to remove any unnecessary whitespace
    }
}
