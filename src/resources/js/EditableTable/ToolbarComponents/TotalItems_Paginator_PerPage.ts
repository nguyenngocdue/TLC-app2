import { TableParams } from '../Type/EditableTable3ParamType'
import { Paginator } from './Paginator'
import { PerPage } from './PerPage'
import { TotalItems } from './TotalItems'

export class TotalItems_Paginator_PerPage {
    constructor(private params: TableParams) {}
    render() {
        const totalItems = new TotalItems(this.params)
        const paginator = new Paginator(this.params)
        const perPage = new PerPage(this.params)

        return `<div class="flex items-center gap-1">
            ${totalItems.render()}
            ${paginator.render()}
            ${perPage.render()}
        </div>`
    }
}
