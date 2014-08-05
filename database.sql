PRAGMA foreign_keys = ON;

CREATE TABLE 'videos' (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	title VARCHAR UNIQUE NOT NULL,
	parent_series int REFERENCES videos(id) ON DELETE RESTRICT,
	is_series BOOLEAN NOT NULL,
	CHECK ((NOT is_series) OR parent_series == null) -- If this is a series, its parent must be null.
);

CREATE TABLE 'metadata' (
	video_id INT REFERENCES videos(id) ON DELETE CASCADE,
	key VARCHAR NOT NULL,
	value VARCHAR NOT NULL,
	UNIQUE(video_id, key)
);
	
CREATE TABLE 'sources' (
	video_id INT REFERENCES videos(id) ON DELETE RESTRICT, -- should be deleted by software first, to make sure files are also removed.
	filename VARCHAR UNIQUE NOT NULL,
	type VARCHAR NOT NULL -- (MIME type)
	-- Only real videos (not serieses) should have sources; SQLite doesn't like this constraint though.
	--CHECK (NOT (SELECT is_series FROM videos WHERE videos.id == video_id))
);

CREATE TABLE 'tracks' (
	video_id INT REFERENCES videos(id) ON DELETE RESTRICT, -- see explanation above
	filename VARCHAR NOT NULL,
	kind VARCHAR NOT NULL CHECK (kind IN ("captions", "chapters", "descriptions", "metadata", "subtitles")),
	label VARCHAR,
	srclang VARCHAR,
	UNIQUE (video_id, kind, srclang, label),
	CHECK (kind != "subtitles" OR srclang != "") --  If the kind attribute is set to subtitles, then srclang must be defined.
	-- Only real videos (not serieses) should have sources; SQLite doesn't like this constraint though.
	--CHECK (NOT (SELECT is_series FROM videos WHERE videos.id == video_id))
);
