let user = document.head.querySelector('meta[name="user"]');

module.exports = {
    computed: {
        currentUser() {
            return JSON.parse(user.content);
        },
        isAuthenticated(){
            return !! user.content;
        },
        isSuperAdmin() {
            return !! (this.isAuthenticated && this.currentUser.email === 'aruiz@tavera.pe');
        },
        appUrl() {
            if (process.env.MIX_APP_ENV === 'production')
                return process.env.MIX_APP_URL
            else
                return '/'
        }

    }
}
