package com.example.sixre.firstapplication;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.ButtonBarLayout;
import android.util.Log;
import android.view.Display;
import android.view.KeyEvent;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.Map;

import co.tanvas.haptics.service.app.*;
import co.tanvas.haptics.service.adapter.*;
import co.tanvas.haptics.service.model.*;

import static android.content.ContentValues.TAG;


public class MainActivity extends AppCompatActivity {

    private HapticView mHapticView;
    private HapticTexture mHapticTexture;
    private HapticMaterial mHapticMaterial;
    private HapticSprite mHapticSprite;
    Bitmap hapticBitmap;
    EditText et;
    Button send;
    private ProgressDialog pDialog;

View view;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        TextView t = (TextView) findViewById(R.id.t1);
        t.setText(Login.c_type_array[doctor.id_1]);
        TextView score = (TextView) findViewById(R.id.t2);
        score.setText(Login.score[doctor.id_1]);
        et = (EditText) findViewById(R.id.feedback);

        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);

        send = (Button) findViewById(R.id.sendfeed);
        send.setOnClickListener(new View.OnClickListener() {

            public void onClick(View view) {
                String feed = et.getText().toString().trim();

                // Check for empty data in the form
                if (!feed.isEmpty()) {
                    // login user
                    checkLogin(feed, Login.u_email_array[doctor.id_1]);
                } else {
                    // Prompt user to enter credentials
                    Toast.makeText(getApplicationContext(),
                            "Please enter the feedback!", Toast.LENGTH_LONG)
                            .show();
                }
            }

        });

//        EditText feed = (EditText)
        //ImageView iv = (ImageView)findViewById(R.id.iv);
        //iv.setBackgroundResource(R.drawable.buzz);
        Toast.makeText(getApplicationContext(), Login.url_array[doctor.id_1].toString(), Toast.LENGTH_SHORT).show();
        new DownloadImageTask((ImageView) findViewById(R.id.iv))
                .execute("http://tanvas.000webhostapp.com/"+Login.url_array[doctor.id_1]);

        //iv.setImageBitmap(getBitmapFromURL("http://tanvas.000webhostapp.com/"+Login.url_array[doctor.id_1]));
        initHaptics();

    }

    @Override
    protected void onResume() {
        super.onResume();
        new DownloadImageTask((ImageView) findViewById(R.id.iv))
                .execute("http://tanvas.000webhostapp.com/"+Login.url_array[doctor.id_1]);
    }

    private void showDialog() {
        if (!pDialog.isShowing())
            pDialog.show();
    }

    private void hideDialog() {
        if (pDialog.isShowing())
            pDialog.dismiss();
    }


    private void checkLogin(final String feed, final String email) {
        // Tag used to cancel the request
        String tag_string_req = "req_login";

        //pDialog.setMessage("Logging in ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                "http://tanvas.000webhostapp.com/addPost.php", new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Login Response: " + response.toString());
                hideDialog();

                try {
                    JSONObject jObj = new JSONObject(response);


                    //System.out.println(response.toString());
                    String error = jObj.getString("success");

                    // Check for error node in json
                    if (error.equalsIgnoreCase("true")) {
                        // user successfully logged in
                        // Create login session


                        //session.setLogin(true);

                        Toast.makeText(getApplicationContext(), "Successfully sent feedBack", Toast.LENGTH_LONG).show();
                        //Toast.makeText(getApplicationContext(), type, Toast.LENGTH_LONG).show();

                        // Inserting row in users table

                        // Launch main activity

                    } else {
                        // Error in login. Get the error message
                        String errorMsg = jObj.getString("error_msg");
                        Toast.makeText(getApplicationContext(),
                                errorMsg, Toast.LENGTH_LONG).show();
                    }
                } catch (JSONException e) {
                    // JSON error
                    e.printStackTrace();
                    Toast.makeText(getApplicationContext(), "Json error: " + e.getMessage(), Toast.LENGTH_LONG).show();
                }

            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Login Error: " + error.getMessage());
                Toast.makeText(getApplicationContext(),
                        error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting parameters to login url
                Map<String, String> params = new HashMap<String, String>();
                params.put("u_email", email);
                params.put("response", feed);

                return params;
            }

        };

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(strReq, tag_string_req);
    }


    private class DownloadImageTask extends AsyncTask<String, Void, Bitmap> {
        ImageView bmImage;

        public DownloadImageTask(ImageView bmImage) {
            this.bmImage = bmImage;
        }

        protected Bitmap doInBackground(String... urls) {
            String urldisplay = urls[0];
            Bitmap mIcon11 = null;
            try {
                InputStream in = new java.net.URL(urldisplay).openStream();
                mIcon11 = BitmapFactory.decodeStream(in);
            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }
            return mIcon11;
        }

        protected void onPostExecute(Bitmap result) {
            bmImage.setImageBitmap(result);
        }
    }
    public static Bitmap getBitmapFromURL(String src) {
        try {
            Log.e("src",src);
            URL url = new URL(src);
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
            connection.setDoInput(true);
            connection.connect();
            InputStream input = connection.getInputStream();
            Bitmap myBitmap = BitmapFactory.decodeStream(input);
            Log.e("Bitmap","returned");
            return myBitmap;
        } catch (IOException e) {
            e.printStackTrace();
            Log.e("Exception",e.getMessage());
            return null;
        }
    }
    /**
     * Override: onWindowFocusChanged
     * <p/>
     * <p>This is the callback that the whole {@link android.app.Activity} has been laid out on the screen.
     * This is also where we set the size and position of the haptic sprite since it is somehow associated with the actual layout of the
     * red rectangle view.</p>
     *
     * @param hasFocus boolean value specifying whether the {@link android.app.Activity} is laid out or hidden in the background
     */
    @Override
    public void onWindowFocusChanged(boolean hasFocus) {
        super.onWindowFocusChanged(hasFocus);

        if (hasFocus) {
            try {
                // Set the size and position of the haptic sprite to correspond to the view we created
                View view = findViewById(R.id.view);
                int[] location = new int[2];
                view.getLocationOnScreen(location);
                mHapticSprite.setSize(view.getWidth(), view.getHeight());
                mHapticSprite.setPosition(location[0], location[1]);
            } catch (Exception e) {
                Log.e(null, e.toString());
            }
        }
    }

    /**
     * Override: onDestroy
     * <p/>
     * <p>When the application quits or switches orientation, we will need to release all the haptic resources.</p>
     */
    @Override
    public void onDestroy() {
        super.onDestroy();
        try {
            mHapticView.deactivate();
        } catch (Exception e) {
            Log.e(null, e.toString());
        }
    }

    /**
     * Initiate haptics
     * <p>
     * <p>This method initializes the haptic variables.</p>
     */
    private void initHaptics() {
        try {
            // Get the service adapter
            HapticServiceAdapter serviceAdapter = HapticApplication.getHapticServiceAdapter();

            // Create a haptic view and activate it
            mHapticView = HapticView.create(serviceAdapter);
            mHapticView.activate();

            // Set the orientation of the haptic view
            Display display = ((WindowManager) getSystemService(Context.WINDOW_SERVICE)).getDefaultDisplay();
            int rotation = display.getRotation();
            HapticView.Orientation orientation = HapticView.getOrientationFromAndroidDisplayRotation(rotation);
            mHapticView.setOrientation(orientation);

            // Retrieve texture data from the bitmap
            if (Login.c_type_array[doctor.id_1].equalsIgnoreCase("rash"))
                hapticBitmap = BitmapFactory.decodeResource(getResources(), R.drawable.rash);
            else if (Login.c_type_array[doctor.id_1].equalsIgnoreCase("moles"))
                hapticBitmap = BitmapFactory.decodeResource(getResources(), R.drawable.moles);
            else if (Login.c_type_array[doctor.id_1].equalsIgnoreCase("acne"))
                hapticBitmap = BitmapFactory.decodeResource(getResources(), R.drawable.acne);

            else if (Login.c_type_array[doctor.id_1].equalsIgnoreCase("clear"))
                hapticBitmap = BitmapFactory.decodeResource(getResources(), R.drawable.clear);


            byte[] textureData = HapticTexture.createTextureDataFromBitmap(hapticBitmap);

            // Create a haptic texture with the retrieved texture data
            mHapticTexture = HapticTexture.create(serviceAdapter);
            int textureDataWidth = hapticBitmap.getRowBytes() / 4; // 4 channels, i.e., ARGB
            int textureDataHeight = hapticBitmap.getHeight();
            mHapticTexture.setSize(textureDataWidth, textureDataHeight);
            mHapticTexture.setData(textureData);

            // Create a haptic material with the created haptic texture
            mHapticMaterial = HapticMaterial.create(serviceAdapter);
            mHapticMaterial.setTexture(0, mHapticTexture);

            // Create a haptic sprite with the haptic material
            mHapticSprite = HapticSprite.create(serviceAdapter);
            mHapticSprite.setMaterial(mHapticMaterial);

            // Add the haptic sprite to the haptic view
            mHapticView.addSprite(mHapticSprite);
        } catch (Exception e) {
            Log.e(null, e.toString());
        }
    }

    @Override
    public void onBackPressed() {
        // your code.
        Toast.makeText(getApplicationContext(),
                "Please enter the feedback!", Toast.LENGTH_LONG)
                .show();
        finish();
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            // your code
            Toast.makeText(getApplicationContext(),
                    "Please enter the feedback!", Toast.LENGTH_LONG)
                    .show();
            finish();
            return true;
        }

        return super.onKeyDown(keyCode, event);
    }

    @Override
    protected void onPause() {
        super.onPause();
        Toast.makeText(getApplicationContext(),
                "Please enter the feedback!", Toast.LENGTH_LONG)
                .show();
        finish();
    }
}
