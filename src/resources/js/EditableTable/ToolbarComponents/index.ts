import { TableParams } from '../Type/EditableTable3ParamType'
import { HelloWorld } from './HelloWorld'
import { Paginator } from './Paginator'
import { PerPage } from './PerPage'
import { TotalItems } from './TotalItems'
import { TotalItems_Paginator } from './TotalItems_Paginator'
import { TotalItems_Paginator_PerPage } from './TotalItems_Paginator_PerPage'

export const ToolbarComponents = {
    helloWorld: HelloWorld,

    totalItems_Paginator_PerPage: TotalItems_Paginator_PerPage,
    totalItems_Paginator: TotalItems_Paginator,
    perPage: PerPage,
    totalItems: TotalItems,
    paginator: Paginator,
}

export class ToolbarComponent {
    private params: TableParams
    constructor(params: TableParams) {
        this.params = params
    }

    render(componentName: keyof typeof ToolbarComponents) {
        if (ToolbarComponents[componentName]) {
            const theClass = ToolbarComponents[componentName]
            // console.log('ToolbarComponent.render()', componentName, theClass)
            return new theClass(this.params).render()
        } else {
            return `Unknown component [${componentName}]`
        }
    }
}
