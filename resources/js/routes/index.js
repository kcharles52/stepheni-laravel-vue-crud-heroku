import Welcome from '../views/Welcome';
import Dashboard from '../views/Dashboard';

export default [
  { path: '/', alias: '', name: 'Welcome', component: Welcome },
  { path: '/home', alias: '/dashboard', name: 'Dashboard', component: Dashboard },
];
