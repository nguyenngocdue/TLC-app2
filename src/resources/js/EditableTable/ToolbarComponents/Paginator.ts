import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }

export class Paginator {
    private lengthAware: LengthAware

    constructor(params: TableParams) {
        const tableName = params.tableName
        const dataSource = tableData[tableName]
        this.lengthAware = dataSource as LengthAware
        // console.log(this.lengthAware)
    }

    generateLinks(classList: string) {
        return this.lengthAware.links.map((link) => {
            const iconPrev = `<i class="fas fa-angle-left"></i>`
            const iconNext = `<i class="fas fa-angle-right"></i>`
            if (link.url) {
                switch (true) {
                    case link.label.includes('Previous'):
                        return `<span class="${classList}">${iconPrev}</span>`
                    case link.label.includes('Next'):
                        return `<span class="${classList}">${iconNext}</span>`
                    default:
                        const disabled = link.active ? 'disabled' : ''
                        return `<a href="${link.url}" class="${classList} ${disabled}">${link.label}</a>`
                }
            } else {
                switch (true) {
                    case link.label === '...':
                        return `.....`
                    default:
                        return ``
                }
            }
        })
    }

    render(): string {
        const { current_page, last_page, first_page_url, last_page_url } = this.lengthAware

        const classList = `focus:shadow-outline-purple rounded border border-r-0 m-0.5 px-0.5 text-white transition-colors duration-150 focus:outline-none border-purple-600 bg-purple-600`

        // Generate links
        const iconFirst = `<i class="fas fa-angle-double-left"></i>`
        const iconLast = `<i class="fas fa-angle-double-right"></i>`

        const links = this.generateLinks(classList)
        let first_page_btn = ``
        if (current_page > 1)
            first_page_btn = `<a href="${first_page_url}" class="${classList}">${iconFirst}</a>`
        let last_page_btn = ``
        if (current_page < last_page)
            last_page_btn = `<a href="${last_page_url}" class="${classList}">${iconLast}</a>`

        // Build the paginator as a string
        const paginator = `
            <div class="paginator">
                <div class="flex items-baseline my-1">
                    ${first_page_btn}
                    ${links.join('')}
                    ${last_page_btn}
                </div>
            </div>
        `

        return paginator.trim() // Trim to remove unnecessary whitespace
    }
}
