import './bootstrap';

// import { CognifitSdk, CognifitSdkConfig } from '@cognifit/launcher-js-sdk';
import { CognifitSdkConfig } from '@cognifit/launcher-js-sdk/lib/lib/cognifit.sdk.config';
import { CognifitSdk } from '@cognifit/launcher-js-sdk';

let cognifitSdk;

document.addEventListener('DOMContentLoaded', function () {
    console.log('CogniFit SDK Initializing...');

    // console.log("{{ Session::get('cognifitUserAccessToken') }}");
    // const userToken = localStorage.getItem("access_token");

    // console.log(userToken);

    const initCognifit = async () => {
        const AUTH_TOKEN = localStorage.getItem("AUTH_TOKEN");
        console.log(JSON.parse(AUTH_TOKEN));

        try {
            if (!AUTH_TOKEN) {
                throw new Error("AUTH_TOKEN is missing in localStorage.");
            }

            const { user_token: userToken, client_id: clientId } = JSON.parse(AUTH_TOKEN);

            if (!userToken || !clientId) {
                throw new Error("Missing user token or client ID.");
            }

            // 🔹 **Langkah 1: Validasi Token ke API Cognifit**
            const access_token = await validateUserToken(AUTH_TOKEN);
            console.log('ini ada error', access_token);
            if (!access_token) {
                throw new Error("Invalid user token. Please login again.");
            }

            const config = new CognifitSdkConfig(
                'cognifit-container',
                clientId,
                access_token,  // Menggunakan token yang sudah divalidasi
                {
                    // sandbox: import.meta.env.VITE_COGNIFIT_SANDBOX,
                    appType: 'web',
                    theme: 'dark',
                    showResults: true,
                    listenEvents: true,
                    customCss: [],
                    // screensNotToShow: [""],
                    // orientation: "portrait",
                    // preferredMobileOrientation: '',
                    isFullscreenEnabled: true,
                    // scale: 800,
                    // customTasks: {},
                    // locale: 'en',
                    // additionalAttributesAndFlags: {
                    //     localeHint: "en",
                    //     performanceTestPreference: "disabled",
                    //     usabilityTestPreference: "disabled",
                    //     taskRatingPreference: "allowed",
                    //     trainingTaskResultsPreference: "allowed",
                    //     trainingTaskPlayAgainPreference: "allowed"
                    // }
                },
            );

            cognifitSdk = new CognifitSdk();
            await cognifitSdk.init(config);
            console.log('CogniFit SDK Initialized Successfully');
        } catch (error) {
            console.error('CogniFit SDK Initialization Failed:', error);
        }
    };

    initCognifit();
});

async function validateUserToken(AUTH_TOKEN) {
    try {
        const { user_token: userToken, client_id: clientId, client_secret: clientSecret } = JSON.parse(AUTH_TOKEN);

        const response = await fetch('https://api.cognifit.com/issue-access-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "client_id": clientId,
                "client_secret": clientSecret,
                "user_token": userToken
            })
        });

        const data = await response.json(); // Konversi response ke JSON lebih awal

        if (!response.ok) {
            console.warn("Token validation failed. Response:", data);
            return false;
        }

        console.log("Token validation success:", data);

        // Mengembalikan access_token agar bisa digunakan dalam CognifitSdkConfig
        return data.access_token;
    } catch (error) {
        console.error("Error validating token:", error);
        return false;
    }
}


window.startGame = function (gameKey) {
    if (!cognifitSdk) {
        console.error('CogniFit SDK is not initialized yet.');
        return;
    }

    cognifitSdk.start('GAME', gameKey).subscribe({
        next: (cognifitSdkResponse) => {
            if (cognifitSdkResponse.isSessionCompleted()) {
                cognifitSdkResponse.typeValue;
                cognifitSdkResponse.keyValue;
            }
            if (cognifitSdkResponse.isSessionAborted()) {

            }
            if (cognifitSdkResponse.isErrorLogin()) {
                alert('Refresh halaman');
            }
            if (cognifitSdkResponse.isEvent()) {
                console.log('Game Event:', cognifitSdkResponse.eventPayload.getValues());
            }
        },
        error: (error) => {
            console.error('Game Error:', error);
            alert('An error occurred while playing the game');
        },
        complete: () => {
            console.log('Game session completed');
        }
    });
};
