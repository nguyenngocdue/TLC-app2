export const UserColumns = [
    {
        dataIndex: "no",
        title: "No.",
        renderer: '_no_',
        fixed: 'left',
    },
    {
        dataIndex: "action",
        title: "Action",
        renderer: 'action',
        fixed: 'left',
    },
    {
        dataIndex: "name",
        title: "Name",
        renderer: 'text',
        control: 'text',
        // footer: 'agg_sum',
        fixed: 'left',
    },
    {
        dataIndex: "country_id",
        title: "Country",
        renderer: 'dropdown',
        control: 'dropdown',
        dataSource:
        {
            84: `Vietnam`,
            1: `US`,
            61: `AU`
        },
    },
    // {
    //     dataIndex: "description",
    //     title: "Description",
    //     renderer: 'text',
    //     control: 'text',
    //     width: 100,
    // },
    {
        dataIndex: "is_female",
        title: "Is Female",
        renderer: 'toggle',
        control: 'toggle',
        footer: 'agg_count',
    },
    {
        dataIndex: "is_resigned",
        title: "Is Resigned",
        renderer: 'toggle',
        control: 'toggle',
        footer: 'A Text',
    },
    {
        dataIndex: "dob",
        title: "Date Of Birth",
        renderer: 'picker',
        control: 'picker',
    },
    {
        dataIndex: "tob",
        title: "Time Of Birth",
        renderer: 'text',
        control: 'text',
    },

    {
        dataIndex: "languages",
        title: "Languages",
        renderer: 'toggle',
        control: 'toggle',
        fixed: 'right',
    },
    {
        dataIndex: "hidden",
        title: "Hidden Column",
        renderer: 'text',
        control: 'text',
        hidden: true,
    },
    {
        dataIndex: "no_renderer",
        title: "No Renderer",
        footer: 'agg_sum',
        fixed: 'right',
    },
]