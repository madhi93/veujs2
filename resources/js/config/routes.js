let routes = [{
        path: '/dashboard',
        component: require('../pages/Dashboard.vue').default, 
        name: 'dashboard',
    },
    {
        path: '/',
        redirect: '/dashboard',
    },
    {
        path: '/admin/users',
        component: require('../components/admin/user/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/user/list.vue').default,
                name: 'Users',
            },
            {
                path: 'create',
                component: require('../components/admin/user/create.vue').default,
                name: 'createUser',
            },
            {
                path: ':user_id/edit',
                component: require('../components/admin/user/edit.vue').default,
                name: 'editUser',
            },
        ]
    },
    {
        path: '/delivery-hubs',
        component: require('../components/admin/hub/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/hub/list.vue').default,
                name: 'DeliveryHubs',
            },
            {
                path: 'create',
                component: require('../components/admin/hub/create.vue').default,
                name: 'createDeliveryHub',
            },
            {
                path: ':hub_id/edit',
                component: require('../components/admin/hub/edit.vue').default,
                name: 'editDeliveryHub',
            },
            {
                path: ':hub_id/delivery-areas',
                component: require('../components/admin/hub/areas.vue').default,
                name: 'DeliveryAreas',
            },
        ]
    },
    {
        path: '/delivery-slots',
        component: require('../components/admin/slot/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/slot/list.vue').default,
                name: 'DeliverySlots',
            },
            {
                path: 'create',
                component: require('../components/admin/slot/create.vue').default,
                name: 'createDeliverySlot',
            },
            {
                path: ':slot_id/edit',
                component: require('../components/admin/slot/edit.vue').default,
                name: 'editDeliverySlot',
            },
        ]
    },
    {
        path: '/admin/roles',
        component: require('../components/admin/role/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/role/list.vue').default,
                name: 'Roles',
            },
            {
                path: 'create',
                component: require('../components/admin/role/create.vue').default,
                name: 'createRole',
            },
            {
                path: ':role_id/edit',
                component: require('../components/admin/role/edit.vue').default,
                name: 'editRole',
            },
            {
                path: ':role_id/permissions',
                component: require('../components/admin/role/permission.vue').default,
                name: 'rolePermissions',
            },            
        ]
    },
    {
        path: '/admin/permissions',
        component: require('../components/admin/permission/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/permission/list.vue').default,
                name: 'Permissions',
            },
            {
                path: 'create',
                component: require('../components/admin/permission/create.vue').default,
                name: 'createPermission',
            },
            {
                path: ':permission_id/edit',
                component: require('../components/admin/permission/edit.vue').default,
                name: 'editPermission',
            },
        ]
    },
    {
        path: '/shop-categories',
        component: require('../components/shop_categories/index.vue').default,
        children: [{
                path: '',
                component: require('../components/shop_categories/list.vue').default,
                name: 'ShopCategories',
            },
            {
                path: 'create',
                component: require('../components/shop_categories/create.vue').default,
                name: 'createShopCategory',
            },
            {
                path: ':category_id/edit',
                component: require('../components/shop_categories/edit.vue').default,
                name: 'editShopCategory',
            },
        ]
    },
    {
        path: '/shops',
        component: require('../components/shops/index.vue').default,
        children: [{
                path: '',
                component: require('../components/shops/list.vue').default,
                name: 'Shops',
            },
            {
                path: 'create',
                component: require('../components/shops/create.vue').default,
                name: 'createShop',
            },
            {
                path: ':shop_id/edit',
                component: require('../components/shops/edit.vue').default,
                name: 'editShop',
            },
        ]
    },
    {
        path: '/shops/:shop_id/products',
        component: require('../components/products/index.vue').default,
        children: [{
                path: '',
                component: require('../components/products/list.vue').default,
                name: 'shopProducts',
            },
            {
                path: 'create',
                component: require('../components/products/create.vue').default,
                name: 'createShopProduct',
            },
            {
                path: ':product_id/edit',
                component: require('../components/products/edit.vue').default,
                name: 'editShopProduct',
            },
        ]
    },
    {
        path: '/product-categories',
        component: require('../components/product_categories/index.vue').default,
        children: [{
                path: '',
                component: require('../components/product_categories/list.vue').default,
                name: 'Categories',
            },
            {
                path: 'create',
                component: require('../components/product_categories/create.vue').default,
                name: 'createCategory',
            },
            {
                path: ':category_id/edit',
                component: require('../components/product_categories/edit.vue').default,
                name: 'editCategory',
            },
        ]
    },
    {
        path: '/products',
        component: require('../components/products/index.vue').default,
        children: [{
                path: '',
                component: require('../components/products/list.vue').default,
                name: 'Products',
            },
            {
                path: 'create',
                component: require('../components/products/create.vue').default,
                name: 'createProduct',
            },
            {
                path: ':product_id/edit',
                component: require('../components/products/edit.vue').default,
                name: 'editProduct',
            },
        ]
    },
    {
        path: '/advertisements',
        component: require('../components/admin/advertisements/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/advertisements/list.vue').default,
                name: 'Advertisements',
            },
            {
                path: 'create',
                component: require('../components/admin/advertisements/create.vue').default,
                name: 'createAdvertisement',
            },
            {
                path: ':advertisement_id/edit',
                component: require('../components/admin/advertisements/edit.vue').default,
                name: 'editAdvertisement',
            },
        ]
    },
    {
        path: '/orders',
        component: require('../components/order/index.vue').default,
        children: [{
                path: '',
                component: require('../components/order/list.vue').default,
                name: 'Orders',
            },
            {
                path: ':order_id',
                component: require('../components/order/view.vue').default,
                name: 'viewOrder',
            },
            // {
            //     path: ':category_id/edit',
            //     component: require('../components/product/edit.vue').default,
            //     name: 'editOrder',
            // },
        ]
    },
    {
        path: '/deliveries',
        component: require('../components/delivery/index.vue').default,
        children: [{
                path: '',
                component: require('../components/delivery/list.vue').default,
                name: 'Deliveries',
            },
            // {
            //     path: 'create',
            //     component: require('../components/delivery/create.vue').default,
            //     name: 'createDelivery',
            // },
            // {
            //     path: ':category_id/edit',
            //     component: require('../components/delivery/edit.vue').default,
            //     name: 'editDelivery',
            // },
        ]
    },
    {
        path: '/customers',
        component: require('../components/customer/index.vue').default,
        children: [{
                path: '',
                component: require('../components/customer/list.vue').default,
                name: 'Customers',
            },
            {
                path: 'create',
                component: require('../components/customer/create.vue').default,
                name: 'createCustomer',
            },
            {
                path: ':customer_id/edit',
                component: require('../components/customer/edit.vue').default,
                name: 'editCustomer',
            },
        ]
    },
    {
        path: '/purchases',
        component: require('../components/purchase/index.vue').default,
        children: [{
                path: '',
                component: require('../components/purchase/list.vue').default,
                name: 'Purchases',
            },
            {
                path: 'create',
                component: require('../components/purchase/create.vue').default,
                name: 'createPurchase',
            },
            {
                path: ':purchase_id/edit',
                component: require('../components/purchase/edit.vue').default,
                name: 'editPurchase',
            },
        ]
    },
    {
        path: '/stocks',
        component: require('../components/stock/index.vue').default,
        children: [{
                path: '',
                component: require('../components/stock/list.vue').default,
                name: 'Stocks',
            },
            {
                path: 'create',
                component: require('../components/stock/create.vue').default,
                name: 'createStock',
            },
            {
                path: ':category_id/edit',
                component: require('../components/stock/edit.vue').default,
                name: 'editStock',
            },
            {
                path: 'products/:product_id',
                component: require('../components/stock/histories.vue').default,
                name: 'productStock',
            },
        ]
    },
    {
        path: '/advertisements',
        component: require('../components/admin/advertisements/index.vue').default,
        children: [{
                path: '',
                component: require('../components/admin/advertisements/list.vue').default,
                name: 'Advertisements',
            },
            {
                path: 'create',
                component: require('../components/admin/advertisements/create.vue').default,
                name: 'createAdvertisement',
            },
            {
                path: ':shop_id/edit',
                component: require('../components/admin/advertisements/edit.vue').default,
                name: 'editAdvertisement',
            },
        ]
    },
    {
        path: '*',
        component: require('../components/NotFound.vue').default
    },
];

export default routes;
