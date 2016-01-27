mysql certigate -u root -ptestpass -N -e 'show tables like "qa\_%"' | xargs mysqldump certigate -u root -ptestpass > qaDumpSql.sql
mysql -u root -ptestpass -D certigate -e "DROP TABLE qa_blobs,qa_cache,qa_categorymetas,qa_contentwords,qa_cookies,qa_iplimits,qa_options,qa_pages,qa_postmetas,qa_posttags,qa_sharedevents,qa_tagmetas,qa_tagwords,qa_titlewords,qa_userevents,qa_userfavorites,qa_userlevels,qa_userlimits,qa_usermetas,qa_usernotices,qa_userpoints,qa_uservotes,qa_widgets,qa_words;"
mysql -u root -ptestpass -D certigate -e "DROP TABLE qa_posts;"
mysql -u root -ptestpass -D certigate -e "DROP TABLE qa_categories;"
