package com.example.sixre.firstapplication;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SimpleCursorAdapter;
import android.content.Intent;
import android.widget.Toast;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by sixre on 11/12/2016.
 */

public class doctor extends Activity {
    SimpleCursorAdapter mAdapter;
    Intent intent = new Intent();
    private String[] imageURLArray;
    private LayoutInflater inflater;

    SessionManager session;

    public static int id_1;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.doc_image);


        ArrayList<ListItem> listData = getListData();
        session = new SessionManager(getApplicationContext());
        final ListView listView = (ListView) findViewById(R.id.custom_list);
        listView.setAdapter(new CustomListAdapter(this, listData));
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> a, View v, int position, long id) {
                ListItem newsData = (ListItem) listView.getItemAtPosition(position);
                //Toast.makeText(doctor.this,position, Toast.LENGTH_LONG).show();
                id_1 = position;
                Intent intent = new Intent(doctor.this,
                        MainActivity.class);
                startActivity(intent);

            }
        });
    }

    private ArrayList<ListItem> getListData() {
        ArrayList<ListItem> listMockData = new ArrayList<ListItem>();
        //String[] images = getResources().getStringArray(R.array.images_array);
        //String[] headlines = getResources().getStringArray(R.array.headline_array);

        for (int i = 0; i < Login.url_array.length; i++) {
            ListItem newsData = new ListItem();
            newsData.setUrl("http://tanvas.000webhostapp.com/"+Login.url_array[i]);
            newsData.setHeadline(Login.c_type_array[i]);
            newsData.setReporterName(Login.u_email_array[i]);
            listMockData.add(newsData);
        }
        return listMockData;
    }

    @Override
    protected void onPause() {
        super.onPause();
        session.setLogin(false);
    }
}

