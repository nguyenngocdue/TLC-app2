import { twMerge } from 'tailwind-merge'
import { ToolbarComponentParent } from './ToolbarComponentParent'

export class PerPage extends ToolbarComponentParent {
    private options = [10, 15, 20, 30, 40, 50, 100]

    render(): string {
        const { per_page } = this.dataSource

        // Build the `select` element as a string
        const selectHtml = `
            <select class="${twMerge(this.params.tableConfig.classList?.dropdown, `w-30`)}">
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
