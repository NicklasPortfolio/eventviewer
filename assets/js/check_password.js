const app = Vue.createApp({
  data() {
    return {
      password: "", // Håller det nuvarande lösenordet
      passwordError: "", // Håller eventuella felmeddelanden för lösenordet
    };
  },
  watch: {
    // Observerar ändringar i 'password'
    password(newPassword) {
      this.validatePassword(newPassword); // Validera lösenordet när det ändras
    },
  },
  methods: {
    // Metod för att validera lösenordet
    validatePassword(password) {
      const minLength = 8;
      const lowercasePattern = /[a-z]/;
      const uppercasePattern = /[A-Z]/;
      const numberPattern = /\d/;
      const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;

      if (password.length < minLength) {
        this.passwordError = "Password must be at least 8 characters long.";
      } else if (!lowercasePattern.test(password)) {
        this.passwordError =
          "Password must contain at least one lowercase letter.";
      } else if (!uppercasePattern.test(password)) {
        this.passwordError =
          "Password must contain at least one uppercase letter.";
      } else if (!numberPattern.test(password)) {
        this.passwordError = "Password must contain at least one number.";
      } else if (!specialCharPattern.test(password)) {
        this.passwordError =
          "Password must contain at least one special character.";
      }

      // Om lösenordet uppfyller alla krav
      else {
        this.passwordError = ""; // Inga felmeddelanden
      }
    },
  },
});

// Montera appen på #app-elementet i HTML
app.mount("#app");
