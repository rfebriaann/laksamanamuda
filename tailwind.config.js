/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                montserrat: ["Montserrat", "sans-serif"],
                poppins: ["Poppins", "sans-serif"],
                archivo: ["Archivo", "sans-serif"],
                // Add more custom font families as needed
            },
        },
        screens: {
            // sm: '640px',   // Small screens (mobile)
            sm: "375px",
            sml: "425px",
            md: "768px", // Medium screens (tablets)
            lg: "1024px", // Large screens (laptops)
            xl: "1280px", // Extra-large screens (desktops)
        },
    },
    plugins: [],
};
