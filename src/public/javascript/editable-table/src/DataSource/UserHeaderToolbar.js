export const UserHeaderToolbar = {
    name: {
        renderer: 'action',
        action: ['clone'],
    },
    is_female: {
        renderer: 'action',
        action: ['toggle', 'clone'],
    },
    is_resigned: {
        renderer: 'action',
        action: ['toggle'],
    },
    no_control: `some text`,
}
