import { TableParams } from '../Type/EditableTable3ParamType'
import { Paginator } from './Paginator'
import { TotalItems } from './TotalItems'

export class TotalItems_Paginator {
    constructor(private params: TableParams) {}
    render() {
        const totalItems = new TotalItems(this.params)
        const paginator = new Paginator(this.params)

        return `<div class="flex items-center gap-1">
            ${totalItems.render()}
            ${paginator.render()}
        </div>`
    }
}
