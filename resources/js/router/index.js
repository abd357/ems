import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../pages/Auth/LoginView.vue";
import DashboardView from "../pages/DashboardView.vue";
import EmployeesView from "../pages/Employee/EmployeesView.vue";
import CreateEmployeeView from "../pages/Employee/CreateEmployeeView.vue"
// import { useAuthStore } from "../stores/auth";
// import { getEmployee } from "../stores/employee";
import EditEmployeeView from "../pages/Employee/EditEmployeeView.vue";
import ManagerView from "../pages/Manager/ManagerView.vue";
import CreateManagerView from "../pages/Manager/CreateManagerView.vue";
import EditManagerView from "../pages/Manager/EditManagerView.vue";
import EditDepartmentView from "../pages/Department/EditDepartmentView.vue";
import DepartmentView from "../pages/Department/DepartmentView.vue";
import CreateDepartmentView from "../pages/Department/CreateDepartmentView.vue";

const routes = [

    {
        path: '/',
        redirect: '/auth/login'
    },
    {
        path: '/home',
        redirect: '/dashboard'
    },
    {
        path: '/auth/login',
        name: 'login',
        component: LoginView,
        // meta: { auth: false }

    },
    {
        path: '/employees',
        name: 'employees',
        component: EmployeesView,
        meta: { requiresAuth : true }
    },
    {
        path: '/create/employee',
        name: 'create-employee',
        component: CreateEmployeeView,
        meta: { requiresAuth : true }
    },

    {
        path: '/edit/employee/:id',
        name: 'get-employee',
        component: EditEmployeeView,
        meta: { requiresAuth : true }
    },
    {
        path: '/edit/manager/:id',
        name: 'get-manager',
        component: EditManagerView,
        meta: { requiresAuth : true }
    },
    {
        path: '/managers',
        name: 'managers',
        component: ManagerView,
        meta: { requiresAuth : true }
    },
    {
        path: '/create/manager',
        name: 'create-manager',
        component: CreateManagerView,
        meta: { requiresAuth : true }
    },
    {
        path: `/departments`,
        name: 'departments',
        component: DepartmentView,
        meta: { requiresAuth : true }
    },
    {
        path: `/edit/department/:id`,
        name: 'edit-department',
        component: EditDepartmentView,
        meta: { requiresAuth : true }
    },
    {
        path: `/create/department`,
        name: 'create-department',
        component: CreateDepartmentView,
        meta: { requiresAuth : true }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardView,
        meta: { requiresAuth : true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    
    const token = localStorage.getItem('token');
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

    if (requiresAuth && !token) {
        next('/auth/login');
    } else if (to.path === '/auth/login' && token) {
        next('/dashboard');
    } else {
        next();
    }
});

export default router